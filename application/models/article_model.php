<?php
Class Article_model extends CI_Model
{
	//Function to get genre from database
	function get_details($cond = NULL,$limit = NULL,$start_limit = NULL,$similar_articles = true,$counter = NULL)
	{
		$this->load->model("commonfn");
		$output = array();
		$this -> db -> select('*');
		$this -> db -> from('articles');
		if($cond != NULL)
			$where = "(status='y' AND ".$cond.")";	
		else
			$where = "(status='y')";
		
		if($start_limit != NULL && $limit != NULL)	
		{
			$this -> db -> limit($limit,$start_limit);	
		}else{
			if($limit != NULL)
				$this -> db -> limit($limit);
		}
		$this->db->where($where);	   
		/*if ($order_by != NULL) {
			$order_by = $order_by;
		}else{
			$order_by = $order_by;
		}*/
		$this->db->order_by("id","desc");
		$query = $this -> db -> get();	   
		if($counter == "counter")
			return $query -> num_rows();
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				if($similar_articles == true){
					$width = 390;
					$height = 194;
					$row["similar_articles"] = $this->get_details('id!='.$row["id"],2,null,false);	
				}
				$row["createdDate"] = date('d F Y',strtotime($row["createdDate"]));
				$row["article_title"] = $row['title'];
				$row["article_photo"] = $this->commonfn->get_photo("art",$row["id"],$width,$height);
				$row["article_main_photo"] = $this->commonfn->get_photo("art",$row["id"],816,437);
				$row["similar_article_url"] = base_url()."article/".$row["perLink"];

				$output[] = $row;
			}
			return $output;
		}
		else{
			return false;
		}	   
	}
}
?>