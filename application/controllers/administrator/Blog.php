<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Blog Controller
*| --------------------------------------------------------------------------
*| Blog site
*|
*/
class Blog extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_blog');
		$this->load->library('storage_manager');
	}

	/**
	* show all Blogs
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('blog_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['blogs'] = $this->model_blog->get($filter, $field, $this->limit_page, $offset);
		$this->data['blog_counts'] = $this->model_blog->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/blog/index/',
			'total_rows'   => $this->model_blog->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Blog List');
		$this->render('backend/standart/administrator/blog/blog_list', $this->data);
	}
	
	/**
	* Add new blogs
	*
	*/
	public function add()
	{
		$this->is_allowed('blog_add');

		$this->template->title('Blog New');
		$this->render('backend/standart/administrator/blog/blog_add', $this->data);
	}

	/**
	* Add New Blogs
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('blog_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('content', 'Content', 'trim|required');
		$this->form_validation->set_rules('blog_image_name[]', 'Image', 'trim');
		$this->form_validation->set_rules('category', 'Category', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('status', 'Status', 'trim|required|max_length[10]');
		

		if ($this->form_validation->run()) {
			$slug = url_title(substr($this->input->post('title') ?? '', 0, 100));
			$save_data = [
				'title' => $this->input->post('title'),
				'slug' => $slug,
				'content' => $this->input->post('content'),
				'tags' => $this->input->post('tags'),
				'category' => $this->input->post('category'),
				'author' => get_user_data('username'),
				'status' => $this->input->post('status'),
				'created_at' => date('Y-m-d H:i:s'),
			];


			$listed_image = [];
			$new_images = [];
			$image_uuids = (array) $this->input->post('blog_image_uuid');
			if (count((array) $this->input->post('blog_image_name'))) {
				foreach ((array) $this->input->post('blog_image_name') as $idx => $file_name) {
					$uuid = $image_uuids[$idx] ?? '';
					$moved = $this->storage_manager->move_from_temp($uuid, $file_name, 'uploads/blog/');
					if (!$moved) {
						$this->cleanup_blog_images($new_images);
						echo json_encode([
							'success' => false,
							'message' => 'Error uploading file'
							]);
						exit;
					}
					$listed_image[] = $moved;
					$new_images[] = $moved;
				}

				$save_data['image'] = implode(',', $listed_image);
			}
		
			
			$save_blog = $this->model_blog->store($save_data);

			if ($save_blog) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_blog;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/blog/edit/' . $save_blog, 'Edit Blog'),
						anchor('administrator/blog', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/blog/edit/' . $save_blog, 'Edit Blog')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/blog');
				}
			} else {
				$this->cleanup_blog_images($new_images);
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/blog');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Blogs
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('blog_update');

		$this->data['blog'] = $this->model_blog->find($id);

		$this->template->title('Blog Update');
		$this->render('backend/standart/administrator/blog/blog_update', $this->data);
	}

	/**
	* Update Blogs
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('blog_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('content', 'Content', 'trim|required');
		$this->form_validation->set_rules('blog_image_name[]', 'Image', 'trim');
		$this->form_validation->set_rules('category', 'Category', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('status', 'Status', 'trim|required|max_length[10]');
		
		if ($this->form_validation->run()) {
			$blog = $this->model_blog->find($id);
			if (!$blog) {
				$this->data['success'] = false;
				$this->data['message'] = cclang('data_not_found');
				echo json_encode($this->data);
				return;
			}
		
			$slug = url_title(substr($this->input->post('slug') ?? '', 0, 100));
			$save_data = [
				'title' => $this->input->post('title'),
				'slug' => $slug,
				'content' => $this->input->post('content'),
				'tags' => $this->input->post('tags'),
				'category' => $this->input->post('category'),
				'author' => get_user_data('username'),
				'status' => $this->input->post('status'),
				'updated_at' => date('Y-m-d H:i:s'),
			];

			$listed_image = [];
			$new_images = [];
			$image_uuids = (array) $this->input->post('blog_image_uuid');
			if (count((array) $this->input->post('blog_image_name'))) {
				foreach ((array) $this->input->post('blog_image_name') as $idx => $file_name) {
					$uuid = $image_uuids[$idx] ?? '';
					if ($uuid !== '') {
						$moved = $this->storage_manager->move_from_temp($uuid, $file_name, 'uploads/blog/');
						if (!$moved) {
							$this->cleanup_blog_images($new_images);
							echo json_encode([
								'success' => false,
								'message' => 'Error uploading file'
								]);
							exit;
						}
						$listed_image[] = $moved;
						$new_images[] = $moved;
					} else {
						$listed_image[] = basename($file_name);
					}
				}
			}
			
			$save_data['image'] = implode(',', $listed_image);
		
			
			$save_blog = $this->model_blog->change($id, $save_data);

			if ($save_blog) {
				$old_images = $this->parse_blog_images($blog->image);
				foreach (array_diff($old_images, $listed_image) as $old_image) {
					$this->storage_manager->delete_if_unreferenced('uploads/blog/', $old_image);
				}
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/blog', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/blog');
				}
			} else {
				$this->cleanup_blog_images($new_images);
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/blog');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Blogs
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('blog_delete');

		$this->load->helper('file');

		$arr_id = $this->input->get('id');
		$remove = false;

		if (!empty($id)) {
			$remove = $this->_remove($id);
		} elseif (count($arr_id) >0) {
			foreach ($arr_id as $id) {
				$remove = $this->_remove($id);
			}
		}

		if ($remove) {
            set_message(cclang('has_been_deleted', 'blog'), 'success');
        } else {
            set_message(cclang('error_delete', 'blog'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Blogs
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('blog_view');

		$this->data['blog'] = $this->model_blog->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Blog Detail');
		$this->render('backend/standart/administrator/blog/blog_view', $this->data);
	}
	
	/**
	* delete Blogs
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$blog = $this->model_blog->find($id);
		if (!$blog || !$this->model_blog->remove($id)) {
			return false;
		}

		$this->cleanup_blog_images($this->parse_blog_images($blog->image));
		return true;
	}

	private function parse_blog_images($value)
	{
		return array_values(array_filter(array_map('basename', array_map('trim', explode(',', (string) $value)))));
	}

	private function cleanup_blog_images(array $images)
	{
		foreach (array_unique($images) as $filename) {
			$this->storage_manager->delete_if_unreferenced('uploads/blog/', $filename);
		}
	}
	
	
	/**
	* Upload Image Blog	* 
	* @return JSON
	*/
	public function upload_image_file()
	{
		if (!$this->is_allowed('blog_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = $this->input->post('qquuid');

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> 'blog',
			'allowed_types' => 'jpg|jpeg|png',
		]);
	}

	/**
	* Delete Image Blog	* 
	* @return JSON
	*/
	public function delete_image_file($uuid)
	{
		if (!$this->is_allowed('blog_delete', false)) {
			echo json_encode([
				'success' => false,
				'error' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		if ($this->input->get('by') !== 'id') {
			echo $this->delete_file(['uuid' => $uuid, 'upload_path_tmp' => './uploads/tmp/']);
			return;
		}

		$blog = $this->model_blog->find($uuid);
		$filename = basename((string) $this->input->get('filename'));
		$images = $blog ? $this->parse_blog_images($blog->image) : [];
		if (!$blog || $filename === '' || !in_array($filename, $images, true)) {
			echo json_encode(['error' => 'File tidak ditemukan.']);
			return;
		}

		$remaining = array_values(array_diff($images, [$filename]));
		$updated = $this->model_blog->change($uuid, ['image' => implode(',', $remaining)]);
		$deleted = $updated && $this->storage_manager->delete_if_unreferenced('uploads/blog/', $filename);
		echo json_encode($deleted ? ['success' => true] : ['error' => 'Error delete file']);
	}

	/**
	* Get Image Blog	* 
	* @return JSON
	*/
	public function get_image_file($id)
	{
		if (!$this->is_allowed('blog_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		$blog = $this->model_blog->find($id);
		$result = [];
		foreach ($blog ? $this->parse_blog_images($blog->image) : [] as $filename) {
			$result[] = [
				'success' => true,
				'thumbnailUrl' => check_is_image_ext(base_url('uploads/blog/' . $filename)),
				'id' => 0,
				'name' => $filename,
				'uuid' => $id,
				'deleteFileEndpoint' => base_url('administrator/blog/delete_image_file'),
				'deleteFileParams' => ['by' => 'id', 'filename' => $filename],
			];
		}
		echo json_encode($result);
	}
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('blog_export');

		$this->model_blog->export('blog', 'blog');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('blog_export');

		$this->model_blog->pdf('blog', 'blog');
	}
}


/* End of file blog.php */
/* Location: ./application/controllers/administrator/Blog.php */
