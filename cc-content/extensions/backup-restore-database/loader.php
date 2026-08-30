<?php 
namespace DisableBuilder;

app()->load->library('cc_html');


app()->cc_html->registerHtmlFileBottom('
    <div class="modal fade settings-restore-modal" id="modal-restore" tabindex="-1" aria-labelledby="modal-restore-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                '.form_open('utils/act/restore', [
                    'name'    => 'form_restore_database',
                    'class'   => 'form-restore',
                    'id'      => 'form-restore',
                    'enctype' => 'multipart/form-data',
                    'method'  => 'POST'
                ]).'
                    <div class="modal-header">
                        <div class="settings-modal-title">
                            <span class="settings-modal-title__icon"><i class="fa fa-upload"></i></span>
                            <div>
                                <h4 class="modal-title" id="modal-restore-title">Pulihkan Database</h4>
                                <p>Unggah cadangan SQL untuk mengganti data database saat ini.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="settings-restore-warning">
                            <i class="fa fa-exclamation-triangle"></i>
                            <div><strong>Tindakan berisiko tinggi</strong><span>Proses ini dapat menimpa data yang sedang digunakan. Buat backup terlebih dahulu.</span></div>
                        </div>
                        <label class="settings-file-upload" for="database_file">
                            <span class="settings-file-upload__icon"><i class="fa fa-file-code-o"></i></span>
                            <span><strong>Pilih file database</strong><small>Format yang didukung: .sql, maksimal 10 MB</small></span>
                            <input type="file" name="database_file" id="database_file" accept=".sql,application/sql,text/sql" required>
                        </label>
                        <div class="settings-selected-file" aria-live="polite">Belum ada file dipilih.</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                        <button type="submit" class="btn btn-danger settings-restore-submit"><i class="fa fa-refresh"></i> Pulihkan Database</button>
                    </div>
                '.form_close().'
            </div>
        </div>
    </div>
');

cicool()->addTabSetting([
    'id' => 'backup_restore_db',
    'label' => 'Backup Restore DB',
    'icon' => 'fa fa-database',
])->addTabContent([
    'content' => '
    <div class="col-md-12 settings-extension settings-database-tools">
        <div class="settings-extension__heading">
            <span class="settings-extension__icon"><i class="fa fa-database"></i></span>
            <div>
                <h2>Backup &amp; Restore Database</h2>
                <p>Lindungi data aplikasi dengan membuat cadangan atau memulihkan database dari file SQL.</p>
            </div>
        </div>

        <div class="settings-extension-grid">
            <section class="settings-operation-card settings-operation-card--safe">
                <div class="settings-operation-card__top">
                    <span class="settings-operation-card__icon"><i class="fa fa-download"></i></span>
                    <span class="settings-operation-badge"><i class="fa fa-shield"></i> Aman</span>
                </div>
                <h3>Backup Database</h3>
                <p>Unduh salinan database saat ini sebagai arsip ZIP untuk disimpan di lokasi yang aman.</p>
                <ul class="settings-operation-list">
                    <li><i class="fa fa-check"></i> Tidak mengubah data aktif</li>
                    <li><i class="fa fa-check"></i> File diunduh otomatis</li>
                </ul>
                <a href="'.base_url('utils/act/backup').'" class="btn btn-primary settings-operation-action settings-operation-action--backup" title="Unduh backup database"><i class="fa fa-download"></i> Buat Backup Sekarang</a>
            </section>

            <section class="settings-operation-card settings-operation-card--danger">
                <div class="settings-operation-card__top">
                    <span class="settings-operation-card__icon"><i class="fa fa-upload"></i></span>
                    <span class="settings-operation-badge"><i class="fa fa-exclamation-triangle"></i> Perlu perhatian</span>
                </div>
                <h3>Restore Database</h3>
                <p>Pulihkan database menggunakan file SQL. Data aktif dapat berubah setelah proses dijalankan.</p>
                <ul class="settings-operation-list">
                    <li><i class="fa fa-check"></i> Hanya menerima file .sql</li>
                    <li><i class="fa fa-check"></i> Maksimal ukuran file 10 MB</li>
                </ul>
                <button type="button" class="btn btn-default btn-restore settings-operation-action settings-operation-action--restore"><i class="fa fa-upload"></i> Pilih File Restore</button>
            </section>
        </div>

        <div class="settings-extension-note"><i class="fa fa-info-circle"></i><span>Simpan file backup di lokasi terpisah dan batasi akses hanya untuk administrator yang berwenang.</span></div>
    </div>

    <script>
    $(function(){
        $(document).off("click.settingsRestore", ".btn-restore").on("click.settingsRestore", ".btn-restore", function(e){
            e.preventDefault();
            var modalElement = document.getElementById("modal-restore");
            if (modalElement && window.bootstrap && window.bootstrap.Modal) {
                window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
            }
        });
        $(document).off("change.settingsRestore", "#database_file").on("change.settingsRestore", "#database_file", function(){
            var fileName = this.files && this.files.length ? this.files[0].name : "Belum ada file dipilih.";
            $(".settings-selected-file").text(fileName);
        });
    });
    </script>
    '
])
->settingBeforeSave(function($form){
})
->settingOnSave(function($ci){
   
});

app()->load->library('aauth');
cicool()->onRoute('utils/act/backup', 'get', function(){
    if (!app()->aauth->is_allowed('db_backup') ) {
        redirect_back();
    }
    app()->load->dbutil();

    $backup = app()->dbutil->backup();

    app()->load->helper('download');
    force_download('mybackup.zip', $backup);
});

cicool()->onRoute('utils/act/restore', 'post', function(){
    if (!app()->aauth->is_allowed('db_restore') ) {
        redirect_back();
    }

    $config['upload_path'] = './uploads/tmp/';
    // SQL is not registered in CodeIgniter's MIME map; validate its extension
    // immediately after upload instead of rejecting every valid backup file.
    $config['allowed_types'] = '*';
    $config['max_size']  = '10240';
    $config['encrypt_name'] = true;
    $config['remove_spaces'] = true;
    
    app()->load->helper('file');
    app()->load->library('upload', $config);

    if ( ! app()->upload->do_upload('database_file')){
        $error = array('error' => app()->upload->display_errors());
        set_message(app()->upload->display_errors(), 'error');
        redirect_back();
    }
    else{
        $data = app()->upload->data();
        $path = './uploads/tmp/'.$data['file_name'];
        if (strtolower($data['file_ext']) !== '.sql') {
          set_message('File harus menggunakan format SQL.', 'error');
          @unlink($path);
          redirect_back();
        }
        $contains = file_get_contents($path);
          $string_query = rtrim( $contains, "\n;" );
          $array_query = explode(";", $string_query);
          foreach($array_query as $query) {
            app()->db->query($query);
          }
          @unlink($path);
          set_message('Database berhasil dipulihkan.');
          redirect_back();
    }
   
});



if(!defined('EXNAMEBRD')) define('EXNAMEBRD', basename(__DIR__));
if ($ccExtension->actived()) {
   app()->cc_app->onEvent('extension_info_'.EXNAMEBRD, function(){
    echo '<div class="callout callout-warning-cc ">go to page '.anchor('administrator/setting/?tab=tab_backup_restore_db', 'setting', ['class' => 'btn btn-xs btn-info btn-flat']).' for configuration</div>';
    });
}
