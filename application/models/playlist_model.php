<?php
Class playlist_model extends CI_Model
{
	
	function __construct(){
		$this->sess_id = $this->session->userdata('user')->id;
		$this->load->model('commonfn');	
	}
	
	//Function to get all playlist from database
	function get_playlists($playlistLink = NULL,$profileLink = NULL){
		
		/*$query = $this->db->select('pl.*,ph.name as path,ph.dir')    
				->from('playlist pl')
				->join('photos ph', 'pl.id = ph.detailid')
				->where('ph.type','pl')
				->order_by("name","asc")
				->get();*/

				$query = $this->db->select('pl.*,ph.name as path,ph.dir')    
				->from('playlist pl')
				->join('photos ph', 'pl.id = ph.detailid')
				->where('ph.type','pl')
				->where('pl.userId',$this->sess_id)
				->order_by("name","asc")
				->get();
				
				$playlist=array();
				foreach ($query->result_array() as $row)
				{
					$row["playlist_image"] = base_url()."assets/upload/".$row["dir"]."/".$row["path"];
					if($profileLink != '')
					{
						$row["pl_perlink"] = base_url().$profileLink."/sets/".$row["perLink"];	
					}
					else
					{
						$row["pl_perlink"] = base_url()."sets/".$row["perLink"];	
					}

					$playlist[]=$row;
				}

				return $playlist;
			}	

	//Function to get all playlist of loggedin user
			function get_my_playlists($trackId,$cond = NULL,$limit = NULL,$orderby = NULL){

				$output = array();

				if($cond != NULL)
					$cond = " WHERE pl.status = 'y' AND userId = '".$this->sess_id."' AND".$cond."";	
				else				
					$cond = " WHERE pl.status = 'y' AND userId = '".$this->sess_id."'";

				if($limit != NULL)
					$limit = " LIMIT ".$limit." ";

				if($orderby != NULL)
					$orderby ="ORDER BY ".$orderby;	
				else
					$orderby ="ORDER BY pl.id DESC";	

				$query = $this->db->query("SELECT pl.id,pl.name,pl.perLink,(SELECT COUNT(id) FROM playlist_detail WHERE playlist_id = pl.id AND userId = '".$this->sess_id."' AND trackId = ".$this->db->escape_str($trackId).") AS song_pl_exist FROM playlist as pl ".$cond." ".$orderby." ".$limit." ");
			//echo print_query();
				foreach ($query->result_array() as $row)
				{
					if($row["song_pl_exist"] > 0)
					{

					}else{
						$row["track_image"] = $this->commonfn->get_photo('pl',$row["id"]);
						$output[] = $row;
					}									
				}
				return $output;		
			}

	//Function to get all playlist from database
			function get_playlist_songs($playlistId,$cond = NULL,$limit = NULL,$orderby = NULL){
				$output = array();

				$playlist_det = getvalfromtbl("name,plays,no_of_track","playlist","id = ".$this->db->escape_str($playlistId)."");
				$output["name"] = $playlist_det["name"]; 
				$output["plays"] = $playlist_det["plays"]; 
				$output["no_of_track"] = $playlist_det["no_of_track"];
				$output["plid"] = $playlistId;

				if($cond != NULL)
					$cond = "  tt.status = 'y' AND pd.playlist_id = ".$this->db->escape_str($playlistId)." AND ".$cond."";	
				else				
					$cond = "  tt.status = 'y' AND pd.playlist_id = ".$this->db->escape_str($playlistId)." ";

				if($limit != NULL)
					$limit = " LIMIT ".$limit." ";

				/*if($orderby != NULL)
					$orderby ="ORDER BY ".$orderby;	
				else
					$orderby ="ORDER BY pd.id DESC";	*/
				
				/*$query = $this->db->query("SELECT tt.id,tt.title,tt.timelength,a.name FROM tracks as tt,albums as a,playlist_detail as pd ".$cond." ".$orderby." ".$limit." ");*/
				$this->db->select('tt.id,tt.title,tt.timelength,a.name');
				$this->db->from('playlist_detail as pd');
				$this->db->join('tracks as tt', 'pd.trackId = tt.id','left');
				$this->db->join('albums as a', 'tt.albumId = a.id','left');
				$this->db->order_by('pd.id',"DESC");

				$this->db->where($cond);
				$query = $this->db->get();
				/*print_query();*/
				$i = 1;
				foreach ($query->result_array() as $row)
				{
					if($i %2 != 0)
						$row["gray_bg"] = "gray-bg";
					else
						$row["gray_bg"] = "";
					$row["index"] = $i;		
					$row["id"] = $row["id"];
					$row["plid"] = $playlistId;
					$row["album_name"] = ($row["name"] == "" || $row["name"] == null) ? '-' : $row["name"];;
					$row["song_name"] = $row["title"];
					$row["song_length"] = $row["timelength"];				
					$row["waveform"] = $row['waveform'];

					$output["songs"][] = $row;
					$i++;											
				}
				return $output;		
			}


			/*function to insert playlist*/
			function insert_playlist($name,$randomnumber,$userId = NULL){
				if($name != NULL || $randomnumber != "")
				{

					$this->load->model('commonfn');
					$permalink = $this->commonfn->get_permalink($name,"playlist","perLink","id");
					$session_user_id = $this->session->userdata('user')->id;
					$file_name_ar = $this->session->userdata('playlist_temp');
					$file_name = $file_name_ar["$randomnumber"];					
					$old_physical_path = asset_path()."playlist_temp/".$file_name;

					$data = array(
						'name' => $name,
						'perLink' => $permalink,
						'userId' => $session_user_id,
						'createdDate'=>date('Y-m-d H:i:s'),
						'updatedDate'=>date('Y-m-d H:i:s')
						);
					$this->db->insert('playlist', $data); 
					$new_physical_path = asset_path()."upload/playlist/".$file_name;
					if (file_exists(asset_path()."playlist_temp/".$file_name)) 
					{
						if (copy($old_physical_path, $new_physical_path)) {
							unlink($old_physical_path);
						}
					}
					$insert_id = $this->db->insert_id();
					$data = array(
						'detailid' => $insert_id,
						'dir' => 'playlist/',
						'name' => $file_name,
						'type'=>'pl'
						);
					$this->db->insert('photos', $data); 
					$response["status"] = "success";
					$response["msg"] = "playlist created successfully.";
					$this->session->unset_userdata('playlist_temp');

				}
				else{
					$response["status"] = "fail";
					$response["msg"] = "playlist not created successfully.";
				}
				return $response;
			}
			/*function to insert playlist*/




		}
		?>