<link rel="stylesheet" href="<?= BASE_ASSET; ?>css/rest.css">
<link rel="stylesheet" href="<?= BASE_ASSET; ?>jquery-ui/jquery-ui.css">
<link rel="stylesheet" href="<?= BASE_ASSET; ?>json-view/jquery.jsonview.css">
<link rel="stylesheet" href="<?= BASE_ASSET; ?>json-editor/dist/jsoneditor.css">

<script src="<?= BASE_ASSET; ?>json-view/jquery.jsonview.js"></script>
<script src="<?= BASE_ASSET; ?>ace-master/build/src/ace.js"></script>
<script src="<?= BASE_ASSET; ?>ace-master/build/src/ext-language_tools.js"></script>
<script src="<?= BASE_ASSET; ?>ace-master/build/src/ext-beautify.js"></script>
<script src="<?= BASE_ASSET; ?>json-editor/dist/jsoneditor.min.js"></script>

<section class="content rest-tool-page">
  <div class="rest-tool-shell">
    <header class="rest-tool-hero">
      <div class="rest-tool-hero__identity">
        <span class="rest-tool-hero__icon" aria-hidden="true"><i class="fa fa-exchange"></i></span>
        <div>
          <span class="rest-tool-eyebrow">API DEVELOPMENT</span>
          <h1>REST API Console</h1>
          <p>Susun, kirim, dan periksa respons endpoint API dalam satu halaman.</p>
        </div>
      </div>
      <div class="rest-tool-hero__actions">
        <a class="btn rest-tool-btn rest-tool-btn--light" href="<?= site_url('administrator/keys'); ?>"><i class="fa fa-key"></i><span>Kunci API</span></a>
        <a class="btn rest-tool-btn rest-tool-btn--light" href="<?= site_url('administrator/doc/api'); ?>"><i class="fa fa-book"></i><span>Dokumentasi API</span></a>
        <a class="btn rest-tool-btn rest-tool-btn--ghost" href="<?= site_url('administrator/rest'); ?>"><i class="fa fa-arrow-left"></i><span>Kembali</span></a>
      </div>
    </header>

    <div class="rest-tool-workspace" id="form_rest">
      <section class="rest-tool-section rest-tool-request-section">
        <div class="rest-tool-section__heading">
          <span class="rest-tool-step">1</span>
          <div><h2>Susun permintaan</h2><p>Pilih metode HTTP dan masukkan URL endpoint tujuan.</p></div>
        </div>

        <div class="rest-tool-request-bar">
          <div class="dropdown rest-tool-method">
            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Pilih metode HTTP"><span class="method-selected">POST</span></button>
            <ul class="dropdown-menu">
              <?php foreach ($methods as $met): ?>
                <li><a href="#" class="dropdown-item switch-method" data-value="<?= _ent(strtoupper($met)); ?>"><?= _ent(strtoupper($met)); ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div class="rest-tool-url-field">
            <i class="fa fa-link" aria-hidden="true"></i>
            <input class="form-control" id="url" placeholder="https://domain.test/api/resource" type="text" autocomplete="off" aria-label="URL permintaan API">
          </div>
          <button type="button" class="btn rest-tool-param-toggle btn-toggle-param" aria-controls="table-param" aria-expanded="false"><i class="fa fa-sliders"></i><span>Parameter</span></button>
          <button type="button" class="btn rest-tool-send btn-send"><i class="fa fa-paper-plane"></i><span>Kirim</span></button>
        </div>

        <div class="rest-tool-param-panel">
          <table class="table-request display-none rest-tool-key-value-table" id="table-param" width="100%" aria-label="Parameter query">
            <tbody><tr>
              <td><input type="text" name="key" placeholder="Nama parameter" class="form-control key"></td>
              <td><input type="text" name="value" placeholder="Nilai parameter" class="form-control value"></td>
              <td class="rest-tool-remove-cell"><button type="button" class="btn btn-remove" aria-label="Hapus parameter"><i class="fa fa-times"></i></button></td>
            </tr></tbody>
          </table>
        </div>
      </section>

      <section class="rest-tool-section rest-tool-data-section">
        <div class="rest-tool-section__heading">
          <span class="rest-tool-step">2</span>
          <div><h2>Atur data permintaan</h2><p>Tambahkan header autentikasi atau data body sesuai kebutuhan endpoint.</p></div>
        </div>

        <div class="rest-tool-tabs">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation"><a href="#tab_1" class="nav-link" data-bs-toggle="tab" role="tab"><i class="fa fa-list-alt"></i> Headers</a></li>
            <li class="nav-item" role="presentation"><a href="#tab_2" class="nav-link active" data-bs-toggle="tab" role="tab"><i class="fa fa-code"></i> Body</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane rest-page-test" id="tab_1" role="tabpanel">
              <div class="rest-tool-table-label"><span>Nama header</span><span>Nilai header</span></div>
              <table class="table-request rest-tool-key-value-table" id="table-headers" width="100%" aria-label="Header permintaan">
                <tbody><tr>
                  <td><input type="text" name="key" placeholder="Contoh: X-Api-Key" class="form-control key"></td>
                  <td><input type="text" name="value" placeholder="Masukkan nilai header" class="form-control value"></td>
                  <td class="rest-tool-remove-cell"><button type="button" class="btn btn-remove" aria-label="Hapus header"><i class="fa fa-times"></i></button></td>
                </tr></tbody>
              </table>
            </div>

            <div class="tab-pane active" id="tab_2" role="tabpanel">
              <div class="rest-tool-table-label rest-tool-table-label--body"><span>Nama field</span><span>Nilai field</span><span>Tipe</span></div>
              <table class="table-request rest-tool-key-value-table rest-tool-body-table" id="table-body" width="100%" aria-label="Body permintaan">
                <tbody><tr>
                  <td><input type="text" name="key" placeholder="Nama field" class="form-control key"></td>
                  <td>
                    <div class="container-input-type container-text"><input type="text" name="value" placeholder="Masukkan nilai" class="form-control value"></div>
                    <label class="file-styling container-input-type container-file display-none">
                      <span class="rest-tool-file-button"><i class="fa fa-upload"></i> Pilih file</span><span class="info-file">Belum ada file dipilih</span>
                      <input type="file" name="file" class="file">
                    </label>
                  </td>
                  <td class="rest-tool-type-cell"><select class="switch-input-type form-select type" aria-label="Tipe data body"><option value="text">Teks</option><option value="file">File</option></select></td>
                  <td class="rest-tool-remove-cell"><button type="button" class="btn btn-remove" aria-label="Hapus body"><i class="fa fa-times"></i></button></td>
                </tr></tbody>
              </table>
            </div>
          </div>
        </div>
      </section>

      <section class="rest-tool-section rest-tool-response-section">
        <div class="rest-tool-section__heading rest-tool-response-heading">
          <div class="rest-tool-heading-copy"><span class="rest-tool-step">3</span><div><h2>Respons API</h2><p>Lihat hasil respons dalam format yang paling mudah diperiksa.</p></div></div>
          <div class="rest-tool-metrics" aria-live="polite">
            <div><span>Status</span><strong class="status">Belum dikirim</strong></div>
            <div><span>Waktu respons</span><strong class="time-requested">—</strong></div>
          </div>
        </div>

        <div class="rest-tool-response-toolbar">
          <div class="btn-group rest-tool-view-switch" role="group" aria-label="Mode tampilan respons">
            <a href="#result-pretty" data-bs-toggle="tab" role="button" class="btn btn-mode btn-mode-pretty"><i class="fa fa-indent"></i> Pretty</a>
            <a href="#result-raw" data-bs-toggle="tab" role="button" class="btn btn-mode btn-mode-raw"><i class="fa fa-file-text-o"></i> Raw</a>
            <a href="#result-preview" data-bs-toggle="tab" role="button" class="btn btn-mode btn-mode-preview active"><i class="fa fa-eye"></i> Preview</a>
          </div>
          <div class="dropdown">
            <button type="button" class="btn rest-tool-format dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><span class="mode-preview-type-selected">JSON</span></button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a href="#" class="dropdown-item btn-mode-preview-type" data-value="json">JSON</a></li>
              <li><a href="#" class="dropdown-item btn-mode-preview-type" data-value="html">HTML</a></li>
            </ul>
          </div>
        </div>

        <div class="tab-content rest-tool-results">
          <pre class="result-pretty tab-pane" id="result-pretty"></pre>
          <pre class="result-raw tab-pane" id="result-raw"></pre>
          <div class="result-preview tab-pane active" id="result-preview"><div class="rest-tool-empty-result"><i class="fa fa-terminal"></i><strong>Belum ada respons</strong><span>Kirim permintaan untuk melihat hasil API.</span></div></div>
          <textarea class="source-fresh display-none" aria-hidden="true"></textarea>
        </div>
      </section>
    </div>
  </div>
</section>

<script src="<?= BASE_ASSET; ?>js/rest-tool.js?v=<?= @filemtime(FCPATH.'asset/js/rest-tool.js'); ?>"></script>
<script>
$(function () {
  var segment = '<?= _ent($this->uri->segment(4)); ?>';

  if (segment === 'get-token') {
    $('#url').val('{api_endpoint}user/request_token');
    addHeaderRequest('X-Api-Key', '');
    addBodyRequest('username', '');
    addBodyRequest('password', '');
    swal({
      title: 'Cara mendapatkan token',
      text: '<p style="text-align:left;line-height:1.7">1. Isi <b>username/email</b> dan <b>password</b> pada tab Body.<br>2. Isi <b>X-Api-Key</b> pada tab Headers.<br>3. Tekan tombol <b>Kirim</b>.</p>',
      html: true
    });
  }

  if ($.fn.autocomplete) {
    $('#url').autocomplete({
      source: function (request, response) {
        $.ajax({url: BASE_URL + 'administrator/rest/get_resource', dataType: 'json', data: {term: request.term}}).done(response);
      }
    });
  }
});
</script>
