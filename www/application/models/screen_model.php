<?php

class Screen_model extends Model {

	function __construct() {
		parent::Model();
		$this->load->model('Dimension_model', 'dimension');
		$this->load->model('Schedule_model', 'schedule');
	}



	function get_list() {

		$this->db->select('id, name, schedule_id, dimension_id');
		$this->db->from('screens');
		$this->db->where('customer_id', $_SESSION['user']['customer_id']);
		$this->db->order_by('name'); 
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['id']] = $row;
				$data[$row['id']]['dimension'] = $this->dimension->get_name($row['dimension_id']);
				$data[$row['id']]['schedule'] = $this->schedule->get_name($row['schedule_id']);
				unset($data[$row['id']]['dimension_id']);
				unset($data[$row['id']]['schedule_id']);
				unset($data[$row['id']]['id']);
			}
		} else {
			$data = array();
		}
		
		return $data;

	}



	function get_one($id = NULL) {
		
		$this->db->select('id, name, schedule_id, dimension_id');
		$this->db->from('screens');
		$this->db->where('customer_id', $_SESSION['user']['customer_id']);
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			$data = $query->row_array();
		} else {
			foreach($query->field_data() as $row) {
				$data[$row->name] = NULL;
			}
		}
		
		return $data;
		
	}



	function delete($id) {
		$this->db->where('id', $id);
		$this->db->delete('screens');
	}



	function update() {
		$data = array(
			'name' => $this->input->post('name'),
			'schedule_id' => $this->input->post('schedule'),
			'dimension_id' => $this->input->post('dimension')
		);
		if($this->input->post('id') > 0) {
			$this->db->where('id', $this->input->post('id'));
			$this->db->update('screens', $data);
		} else {
			$data['customer_id'] = $_SESSION['user']['customer_id'];
			$this->db->insert('screens', $data);
		}
	}



	function md5($id) {

		$this->db->select('s.change_date AS s, cs.change_date AS cs, c.change_date AS c, lc.change_date AS lc, l.change_date AS l, bl.change_date AS bl, b.change_date AS b, mb.change_date AS mb, m.change_date AS m');
		$this->db->from('screens');
		$this->db->join('schedules AS s', 's.id = screens.schedule_id');
		$this->db->join('collections_schedules AS cs', 'cs.schedule_id = s.id');
		$this->db->join('collections AS c', 'c.id = cs.collection_id');
		$this->db->join('layouts_collections AS lc', 'lc.collection_id = c.id');
		$this->db->join('layouts AS l', 'l.id = lc.layout_id');
		$this->db->join('bundles_layouts AS bl', 'bl.layout_id = l.id');
		$this->db->join('bundles AS b', 'b.id = bl.bundle_id');
		$this->db->join('medias_bundles AS mb', 'mb.bundle_id = b.id');
		$this->db->join('medias AS m', 'm.id = mb.media_id');
		$this->db->where('screens.id', $id);
		$query = $this->db->get();
	
		$md5_string = '';
	
		foreach($query->result_array() as $row) {
			$md5_string .= implode(';', $row);
		}
		
		return md5($md5_string);
		
	}

}

?>
