<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class app extends CI_Controller {

	/**
	 * @author : Muchammad Rizal Zulmi
	 * @web : http://mrizalzulmi.wordpress.com
	 * @keterangan : Controller untuk manajemen data rs dinkes DKI
	 **/

	public function api_rs_rujukan()
	{
		$query = $this->db->query("SELECT b.* FROM rs_rujukan a LEFT JOIN rs_dki b ON a.alamat=b.alamat_rumah_sakit OR a.kelurahan=b.kelurahan OR a.kecamatan=b.kecamatan")->result_array();

		if ($query) {
			foreach ($query as $hsl) {
				
                $data = array(
                    'nama_rumah_sakit' => $hsl['nama_rumah_sakit'],
                    'jenis_rumah_sakit' => $hsl['jenis_rumah_sakit'], 
                    'alamat_rumah_sakit' => $hsl['alamat_rumah_sakit'],
					'kelurahan' => $hsl['kelurahan'],
					'kecamatan' => $hsl['kecamatan'],
					'kota' => $hsl['kota'],
					'kode_pos' => $hsl['kode_pos'],
					'nomor_telepon' => $hsl['nomor_telepon'],
					'nomor_fax' => $hsl['nomor_fax'],
					'no_hp_direktur' => $hsl['no_hp_direktur'],
					'website' => $hsl['website'],
					'email' => $hsl['email']
    	        );
              }
        }
		$json_data = json_encode($data);

		echo $json_data;
	}
	 
	// fungsi untuk menarik data dari API RS Rujukan Covid 19
	public function get_data_rs_rujukan()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://data.jakarta.go.id/read-resource/get-json/daftar-rumah-sakit-rujukan-penanggulangan-covid-19/65d650ae-31c8-4353-a72b-3312fd0cc187",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  
			$data_respon = $response;

			$obj = json_decode($data_respon,true);

			$this->db->query('truncate table rs_rujukan');	
			
			// insert into table rs_rujukan			
			foreach ($obj as $data) {
				$ins['nama_rumah_sakit'] = $data['nama_rumah_sakit'];
				$ins['alamat'] = $data['alamat'];
				$ins['kota_madya'] = $data['kota_madya'];
				$ins['kelurahan'] = $data['kelurahan'];
				$ins['kecamatan'] = $data['kecamatan'];

				$this->db->insert('rs_rujukan', $ins);				

			}
		}
	}

	// fungsi untuk menarik seluruh data RS di DKI
	public function get_data_rs_dki()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://data.jakarta.go.id/read-resource/get-json/rsdkijakarta-2017-10/8e179e38-c1a4-4273-872e-361d90b68434",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  	
			$data_respon = $response;
			$obj = json_decode($data_respon,true);

			// truncate table rs dki
			$this->db->query('truncate table rs_dki');
			
			// insert into table rs_dki
			foreach ($obj as $data) {
				$ins['nama_rumah_sakit'] = $data['nama_rumah_sakit'];
				$ins['jenis_rumah_sakit'] = $data['jenis_rumah_sakit'];
				$ins['alamat_rumah_sakit'] = $data['alamat_rumah_sakit'];
				$ins['kelurahan'] = $data['kelurahan'];
				$ins['kecamatan'] = $data['kecamatan'];
				$ins['kota'] = $data['kota/kab_administrasi'];
				$ins['kode_pos'] = $data['kode_pos'];
				$ins['nomor_telepon'] = $data['nomor_telepon'];
				$ins['nomor_fax'] = $data['nomor_fax'];
				$ins['no_hp_direktur'] = $data['no_hp_direktur/kepala_rs'];
				$ins['website'] = $data['website'];
				$ins['email'] = $data['email'];

				$this->db->insert('rs_dki', $ins);				
			}
		}
	}


	 
}

/* End of file app.php */
/* Location: ./application/controllers/app.php */