
<script src="{php_open_tag_echo} BASE_ASSET; {php_close_tag}/js/jquery.hotkeys.js"></script>

<script type="text/javascript">
function domo(){
 
   // Binding keys
   $('*').bind('keydown', 'Ctrl+a', function assets() {
       window.location.href = BASE_URL + '/<?= ucwords($table_name); ?>/add';
       return false;
   });

   $('*').bind('keydown', 'Ctrl+f', function assets() {
       $('#sbtn').trigger('click');
       return false;
   });

   $('*').bind('keydown', 'Ctrl+x', function assets() {
       $('#reset').trigger('click');
       return false;
   });

}

jQuery(document).ready(domo);
</script>

<!-- Main content -->
<section class="content">
   <div class="row" >
      
      <div class="col-md-12">
         <div class="box box-warning">
            <div class="box-body ">
               <!-- Widget: user widget style 1 -->
               <div class="box box-widget widget-user-2">
                  <!-- Add the bg color to the header using any of the bg-* classes -->
                  <div class="widget-user-header ">
                     <div class="row pull-right">
                        <?php if ($this->input->post('create')) { ?>{php_open_tag} is_allowed('<?= $table_name; ?>_add', function(){{php_close_tag}
                        <a class="btn btn-flat btn-success btn_add_new" id="btn_add_new" title="Tambah Data  (Ctrl+a)" href="{php_open_tag_echo}  site_url('<?= $table_name; ?>/add'); {php_close_tag}"><i class="fa fa-plus-square-o" ></i> Tambah Data</a>
                        {php_open_tag} }) {php_close_tag}
                        <?php } ?>{php_open_tag} is_allowed('<?= $table_name; ?>_export', function(){{php_close_tag}
                        <a class="btn btn-flat btn-success" title="{php_open_tag_echo} cclang('export'); {php_close_tag} XLS" href="{php_open_tag_echo} site_url('<?= $table_name; ?>/export'); {php_close_tag}"><i class="fa fa-file-excel-o" ></i></a>
                        {php_open_tag} }) {php_close_tag}
                        {php_open_tag} is_allowed('<?= $table_name; ?>_export', function(){{php_close_tag}
                        <a class="btn btn-flat btn-success" title="{php_open_tag_echo} cclang('export'); {php_close_tag} PDF" href="{php_open_tag_echo} site_url('<?= $table_name; ?>/export_pdf'); {php_close_tag}"><i class="fa fa-file-pdf-o" ></i></a>
                        {php_open_tag} }) {php_close_tag}
                     </div>
                     <div class="widget-user-image">
                        <img class="img-circle" src="{php_open_tag_echo} BASE_ASSET; {php_close_tag}/img/list.png" alt="User Avatar">
                     </div>
                     <!-- /.widget-user-image -->
                     <h3 class="widget-user-username"><b><?= ucwords($subject); ?></b></h3>
                     <h5 class="widget-user-desc">{php_open_tag_echo} cclang('list_all', ['<?= ucwords($subject); ?>']); {php_close_tag}  <i class="label bg-yellow">{php_open_tag_echo} $<?= $table_name; ?>_counts; {php_close_tag}  {php_open_tag_echo} cclang('items'); {php_close_tag}</i></h5>
                  </div>

                  <form name="form_<?= $table_name; ?>" id="form_<?= $table_name; ?>" action="{php_open_tag_echo} base_url('<?= $table_name; ?>/index'); {php_close_tag}">
                  

                  <div class="table-responsive"> 
                  <table class="table table-bordered table-striped dataTable">
                     <thead>
                        <tr class="">
                           <th>
                            <input type="checkbox" class="flat-red toltip" id="check_all" name="check_all" title="check all">
                           </th>
                           <?php foreach ($this->crud_builder->getFieldShowInColumn(true) as $option): ?><th<?= in_array($option['input_type'], ['date', 'datetime'], true) ? ' class="table-date-cell"' : (strtolower(trim($option['label'])) === 'nomor akta' ? ' class="table-number-cell"' : ''); ?> style="text-align:center"><?= ucwords(clean_snake_case($option['label'])); ?></th>
                           <?php endforeach; ?><th style="text-align:center" width="7%">Action</th>
                        </tr>
                     </thead>
                     <tbody id="tbody_<?= $table_name; ?>">
                     {php_open_tag} foreach($<?= $table_name; ?>s as $<?= $table_name; ?>): {php_close_tag}
                        <tr>
                           <td width="5">
                              <input type="checkbox" class="flat-red check" name="id[]" value="{php_open_tag_echo} $<?= $table_name; ?>->{primary_key}; ?>">
                           </td>
                           
                           <?php foreach ($this->crud_builder->getFieldShowInColumn() as $field): ?><?php 
                           $relation = $this->crud_builder->getFieldRelation($field);
                           if (($this->crud_builder->getFieldByType('date', $field) || $this->crud_builder->getFieldByType('datetime', $field)) && !$relation) {
                            ?><td class="table-date-cell">{php_open_tag_echo} _ent(format_date_id($<?= $table_name; ?>-><?= $field; ?>)); {php_close_tag}</td><?php
                           } elseif (!$this->crud_builder->getFieldFile($field) AND !$this->crud_builder->getFieldFileMultiple($field) AND !$relation){
                            ?><td<?= strtolower($field) === 'nomor_akta' ? ' class="table-number-cell"' : ''; ?>>{php_open_tag_echo} _ent($<?= $table_name; ?>-><?= $field; ?>); {php_close_tag}</td><?php } elseif ($relation){
                            ?><td>{php_open_tag_echo} _ent($<?= $table_name; ?>-><?= $relation['relation_label']; ?>); {php_close_tag}</td>
                            <?php } elseif($this->crud_builder->getFieldFileMultiple($field)) { 
                            ?><td>
                              {php_open_tag} foreach (explode(',', $<?= $table_name; ?>-><?= $field; ?>) as $file): {php_close_tag}
                              {php_open_tag} if (!empty($file)): {php_close_tag}
                                {php_open_tag} if (is_image($file)): {php_close_tag}
                                <a class="fancybox" rel="group" href="{php_open_tag_echo} BASE_URL . 'uploads/<?= $table_name; ?>/' . $file; {php_close_tag}">
                                  <img src="{php_open_tag_echo} BASE_URL . 'uploads/<?= $table_name; ?>/' . $file; {php_close_tag}" class="image-responsive" alt="image <?= $table_name; ?>" title="<?= $field; ?> <?= $table_name; ?>" width="40px">
                                </a>
                                {php_open_tag} else: {php_close_tag}
                                  <a href="{php_open_tag_echo} BASE_URL . 'administrator/file/download/<?= $table_name; ?>/' . $file; {php_close_tag}">
                                   <img src="{php_open_tag_echo} get_icon_file($file); {php_close_tag}" class="image-responsive image-icon" alt="image <?= $table_name; ?>" title="<?= $field; ?> {php_open_tag_echo} $file; {php_close_tag}" width="40px"> 
                                 </a>
                                {php_open_tag} endif; {php_close_tag}
                              {php_open_tag} endif; {php_close_tag}
                              {php_open_tag} endforeach; {php_close_tag}
                           </td>
                           <?php } else { 
                            ?><td>
                              {php_open_tag} if (!empty($<?= $table_name; ?>-><?= $field; ?>)): {php_close_tag}
                                {php_open_tag} if (is_image($<?= $table_name; ?>-><?= $field; ?>)): {php_close_tag}
                                <a class="fancybox" rel="group" href="{php_open_tag_echo} BASE_URL . 'uploads/<?= $table_name; ?>/' . $<?= $table_name; ?>-><?= $field;?>; {php_close_tag}">
                                  <img src="{php_open_tag_echo} BASE_URL . 'uploads/<?= $table_name; ?>/' . $<?= $table_name; ?>-><?= $field;?>; {php_close_tag}" class="image-responsive" alt="image <?= $table_name; ?>" title="<?= $field; ?> <?= $table_name; ?>" width="40px">
                                </a>
                                {php_open_tag} else: {php_close_tag}
                                  <a href="{php_open_tag_echo} BASE_URL . 'administrator/file/download/<?= $table_name; ?>/' . $<?= $table_name; ?>-><?= $field;?>; {php_close_tag}">
                                   <img src="{php_open_tag_echo} get_icon_file($<?= $table_name; ?>-><?= $field; ?>); {php_close_tag}" class="image-responsive image-icon" alt="image <?= $table_name; ?>" title="<?= $field; ?> {php_open_tag_echo} $<?= $table_name; ?>-><?= $field; ?>; {php_close_tag}" width="40px"> 
                                 </a>
                                {php_open_tag} endif; {php_close_tag}
                              {php_open_tag} endif; {php_close_tag}
                           </td>
                           <?php } ?> 
                           <?php endforeach; 
                           ?><td width="200">
                              <?php if ($this->input->post('read')) { ?>{php_open_tag} is_allowed('<?= $table_name; ?>_view', function() use ($<?= $table_name; ?>){{php_close_tag}
                              <a href="{php_open_tag_echo} site_url('<?= $table_name; ?>/view/' . ${table_name}->{primary_key}); {php_close_tag}" title="Lihat" class="label-default"><i class="fa fa-newspaper-o"></i></a>
                              {php_open_tag} }) {php_close_tag}
                              <?php } ?><?php if ($this->input->post('update')) { ?>{php_open_tag} is_allowed('<?= $table_name; ?>_update', function() use ($<?= $table_name; ?>){{php_close_tag}
                              <a href="{php_open_tag_echo} site_url('<?= $table_name; ?>/edit/' . ${table_name}->{primary_key}); {php_close_tag}" title="Ubah" class="label-default"><i class="fa fa-edit "></i> </a>
                              {php_open_tag} }) {php_close_tag}
                              <?php } ?>{php_open_tag} is_allowed('<?= $table_name; ?>_delete', function() use ($<?= $table_name; ?>){{php_close_tag}
                              <a href="javascript:void(0);" data-id="{php_open_tag_echo} (int) ${table_name}->{primary_key}; {php_close_tag}" title="Hapus" class="label-default remove-data"><i class="fa fa-close" style="color: red"></i> </a>
                               {php_open_tag} }) {php_close_tag}
                           </td>
                        </tr>
                      {php_open_tag} endforeach; {php_close_tag}
                      {php_open_tag} if ($<?= $table_name; ?>_counts == 0) :{php_close_tag}
                         <tr>
                           <td colspan="100">
                           <?= ucwords($subject); ?> data is not available
                           </td>
                         </tr>
                      {php_open_tag} endif; {php_close_tag}
                     </tbody>
                  </table>
                  </div>
               </div>
               <hr>
               <!-- /.widget-user -->
               <div class="row">
                  <div class="col-md-8">
                     <div class="col-sm-2 padd-left-0 " >
                        <select type="text" class="form-control chosen chosen-select" name="bulk" id="bulk" placeholder="Site Email" >
                           <!--<option value="">Bulk</option>-->
                           <option value="delete" selected="selected">Hapus</option>
                        </select>
                     </div>
                     <div class="col-sm-2 padd-left-0 ">
                        <button type="button" class="btn btn-flat" name="apply" id="apply" title="{php_open_tag_echo} cclang('apply_bulk_action'); {php_close_tag}">{php_open_tag_echo} cclang('apply_button'); {php_close_tag}</button>
                     </div>
                     <div class="col-sm-3 padd-left-0  " >
                        <input type="text" class="form-control" name="q" id="filter" placeholder="{php_open_tag_echo} cclang('filter'); {php_close_tag}" value="{php_open_tag_echo} $this->input->get('q'); {php_close_tag}">
                     </div>
                     <div class="col-sm-3 padd-left-0 " >
                        <select type="text" class="form-control chosen chosen-select" name="f" id="field" >
                           <option value="">{php_open_tag_echo} cclang('all'); {php_close_tag}</option>
                           <?php foreach ($this->crud_builder->getFieldShowInColumn() as $field): 
                          ?> <option {php_open_tag_echo} $this->input->get('f') == '<?= $field; ?>' ? 'selected' :''; {php_close_tag} value="<?= $field; ?>"><?= ucwords(clean_snake_case($field)); ?></option>
                          <?php endforeach;
                        ?></select>
                     </div>
                     <div class="col-sm-1 padd-left-0 ">
                        <button type="submit" class="btn btn-flat" name="sbtn" id="sbtn" value="Apply" title="{php_open_tag_echo} cclang('filter_search'); {php_close_tag}">
                        Filter
                        </button>
                     </div>
                     <div class="col-sm-1 padd-left-0 ">
                        <a class="btn btn-default btn-flat" name="reset" id="reset" value="Apply" href="{php_open_tag_echo} base_url('<?= $table_name; ?>');{php_close_tag}" title="{php_open_tag_echo} cclang('reset_filter'); {php_close_tag}">
                        <i class="fa fa-undo"></i>
                        </a>
                     </div>
                  </div>
                  <?= form_close(); ?>
                  <div class="col-md-4">
                     <div class="dataTables_paginate paging_simple_numbers pull-right" id="example2_paginate" >
                        {php_open_tag_echo} $pagination; {php_close_tag}
                     </div>
                  </div>
               </div>
            </div>
            <!--/box body -->
         </div>
         <!--/box -->
      </div>
   </div>
</section>
<!-- /.content -->

<!-- Page script -->
<script>
  $(document).ready(function(){
   
    $('.remove-data').click(function(){

      var recordId = $(this).attr('data-id');

      swal({
          title: "{php_open_tag_echo} cclang('are_you_sure'); {php_close_tag}",
          text: "{php_open_tag_echo} cclang('data_to_be_deleted_can_not_be_restored'); {php_close_tag}",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "{php_open_tag_echo} cclang('yes_delete_it'); {php_close_tag}",
          cancelButtonText: "{php_open_tag_echo} cclang('no_cancel_plx'); {php_close_tag}",
          closeOnConfirm: true,
          closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm) {
            submitGeneratedCrudDelete([recordId]);
          }
        });

      return false;
    });


    $('#apply').click(function(){

      var bulk = $('#bulk');
      if (bulk.val() == 'delete') {
         swal({
            title: "{php_open_tag_echo} cclang('are_you_sure'); {php_close_tag}",
            text: "{php_open_tag_echo} cclang('data_to_be_deleted_can_not_be_restored'); {php_close_tag}",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "{php_open_tag_echo} cclang('yes_delete_it'); {php_close_tag}",
            cancelButtonText: "{php_open_tag_echo} cclang('no_cancel_plx'); {php_close_tag}",
            closeOnConfirm: true,
            closeOnCancel: true
          },
          function(isConfirm){
            if (isConfirm) {
               var selectedIds = [];
               $('input.check:checked').each(function () { selectedIds.push($(this).val()); });
               if (!selectedIds.length) {
                  swal('Upss', 'Pilih minimal satu data terlebih dahulu.', 'warning');
                  return;
               }
               submitGeneratedCrudDelete(selectedIds);
            }
          });

        return false;

      } else if(bulk.val() == '')  {
          swal({
            title: "Upss",
            text: "{php_open_tag_echo} cclang('please_choose_bulk_action_first'); {php_close_tag}",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Okay!",
            closeOnConfirm: true,
            closeOnCancel: true
          });

        return false;
      }

      return false;

    });/*end appliy click*/


    //check all
    var checkAll = $('#check_all');
    var checkboxes = $('input.check');

    checkAll.on('ifChecked ifUnchecked', function(event) {   
        if (event.type == 'ifChecked') {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });

    checkboxes.on('ifChanged', function(event){
        if(checkboxes.filter(':checked').length == checkboxes.length) {
            checkAll.prop('checked', 'checked');
        } else {
            checkAll.removeProp('checked');
        }
        checkAll.iCheck('update');
    });

  }); /*end doc ready*/

  function submitGeneratedCrudDelete(ids) {
    var form = $('<form>', { method: 'post', action: BASE_URL + '/<?= $table_name; ?>/delete' });
    $.each(ids, function (_, id) {
      form.append($('<input>', { type: 'hidden', name: 'id[]', value: id }));
    });
    form.append($('<input>', {
      type: 'hidden',
      name: '{php_open_tag_echo} $this->security->get_csrf_token_name(); {php_close_tag}',
      value: '{php_open_tag_echo} $this->security->get_csrf_hash(); {php_close_tag}'
    }));
    $('body').append(form);
    form.trigger('submit');
  }
</script>
