<script src="<?= BASE_ASSET; ?>/js/jquery.hotkeys.js"></script>
<link rel="stylesheet" type="text/css" href="<?= BASE_ASSET; ?>nestable/nesteable.css">
<link rel="stylesheet" href="<?= BASE_ASSET; ?>m-switch/css/style.css">
<style>
   .admin-silaris .menu-builder-page { padding: 0 !important; }
   .admin-silaris .menu-builder-container { max-width: 1180px; margin: 0 auto; }
   .admin-silaris .menu-builder-page-header {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      gap: 20px;
      margin-bottom: 20px;
   }
   .admin-silaris .menu-builder-eyebrow {
      display: block;
      margin-bottom: 5px;
      color: #8a6b00;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .09em;
      text-transform: uppercase;
   }
   .admin-silaris .menu-builder-page-header h1 {
      margin: 0 0 5px;
      color: var(--ink-900);
      font-size: 25px;
      font-weight: 800;
      letter-spacing: -.03em;
   }
   .admin-silaris .menu-builder-page-header p { margin: 0; color: var(--ink-500); font-size: 12.5px; }
   .admin-silaris .menu-builder-shortcuts {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      color: var(--ink-500);
      font-size: 11px;
   }
   .admin-silaris .menu-builder-shortcuts kbd {
      padding: 4px 7px;
      border: 1px solid var(--border);
      border-bottom-width: 2px;
      border-radius: 6px;
      background: #fff;
      color: var(--ink-700);
      font-family: inherit;
      font-size: 10px;
      box-shadow: none;
   }
   .admin-silaris .menu-builder-grid { display: flex; align-items: flex-start; row-gap: 20px; }
   .admin-silaris .menu-builder-card {
      overflow: hidden;
      margin: 0;
      border: 1px solid var(--border) !important;
      border-top: 3px solid var(--accent) !important;
      border-radius: var(--radius) !important;
      background: #fff;
      box-shadow: 0 8px 26px rgba(15, 27, 45, .07) !important;
   }
   .admin-silaris .menu-builder-card .box-header {
      display: flex;
      align-items: center;
      gap: 12px;
      min-height: 76px;
      padding: 17px 20px !important;
      border-bottom: 1px solid var(--border) !important;
      background: linear-gradient(135deg, #fff 0%, #fffdf4 100%);
   }
   .admin-silaris .menu-builder-card .box-header::before {
      display: grid;
      flex: 0 0 38px;
      width: 38px;
      height: 38px;
      place-items: center;
      border-radius: 10px;
      background: var(--brand);
      color: var(--accent);
      font-family: FontAwesome;
      font-size: 15px;
      content: "\f0ca";
   }
   .admin-silaris .menu-tree-card .box-header::before { content: "\f0e8"; }
   .admin-silaris .menu-builder-card .box-title {
      margin: 0 !important;
      color: var(--ink-900);
      font-size: 17px !important;
      font-weight: 800 !important;
      letter-spacing: -.015em;
   }
   .admin-silaris .menu-builder-card .box-body { padding: 20px !important; }
   .admin-silaris .menu-type-wrapper {
      min-height: 48px;
      margin-bottom: 9px;
      overflow: hidden;
      border-color: var(--border) !important;
      border-radius: 9px !important;
      background: #fbfcfe !important;
   }
   .admin-silaris .menu-type-wrapper:hover { border-color: #c5ad52 !important; background: var(--accent-tint) !important; }
   .admin-silaris .menu-type-wrapper.active {
      border-color: var(--brand) !important;
      background: var(--brand) !important;
      box-shadow: 0 5px 14px rgba(5, 6, 62, .16);
   }
   .admin-silaris .menu-type-wrapper.active .menu-type { background: transparent !important; color: #fff !important; }
   .admin-silaris .menu-type-wrapper.active .menu-type::before { color: var(--accent); }
   .admin-silaris .menu-type {
      flex: 1 1 auto !important;
      width: auto !important;
      min-width: 0;
      margin: 0 !important;
      padding: 0 14px !important;
      display: flex !important;
      align-items: center;
      gap: 9px;
      min-height: 46px;
      float: none !important;
      border: 0 !important;
      background: transparent !important;
      color: var(--ink-700) !important;
      font-size: 12px;
      font-weight: 700;
   }
   .admin-silaris .menu-type::before { color: var(--ink-300); font-family: FontAwesome; content: "\f0c9"; }
   .admin-silaris .menu-type-action {
      flex: 0 0 42px;
      width: 42px !important;
      min-height: 46px;
      margin: 0 !important;
      padding: 0 !important;
      float: none !important;
      border: 0 !important;
      border-left: 1px solid var(--border) !important;
      background: transparent !important;
      color: var(--danger) !important;
   }
   .admin-silaris .menu-type-action:hover { background: rgba(192, 36, 47, .07) !important; }
   .admin-silaris .menu-type-wrapper.active .menu-type-action {
      border-left-color: rgba(255,255,255,.16) !important;
      background: transparent !important;
      color: #fff !important;
   }
   .admin-silaris .menu-type-action--empty { display: none !important; }
   .admin-silaris .btn-add-menu,
   .admin-silaris .menu-builder-toolbar .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      min-height: 41px;
      padding: 0 15px;
      border-radius: 9px !important;
      font-size: 11.5px;
      font-weight: 750;
   }
   .admin-silaris .btn-add-menu { margin-top: 12px; background: var(--brand) !important; color: #fff !important; }
   .admin-silaris .menu-builder-tip {
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 0 0 15px !important;
      padding: 11px 13px !important;
      border: 1px solid #cbd8f7 !important;
      border-left: 3px solid var(--info) !important;
      border-radius: 9px !important;
      background: var(--info-tint) !important;
      color: #24468c !important;
      font-size: 11.5px;
   }
   .admin-silaris .menu-builder-toolbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 18px;
   }
   .admin-silaris .menu-builder-toolbar .btn { border: 0; background: var(--accent) !important; color: var(--ink-900) !important; }
   .admin-silaris .menu-builder-toolbar .loading { margin-left: auto; color: var(--ink-500); font-size: 11px; }
   .admin-silaris .menu-builder-toolbar .loading img { width: 18px; }
   .admin-silaris .menu-tree-card .dd { width: 100% !important; max-width: none; float: none; }
   .admin-silaris .menu-tree-card .dd-list .dd-list {
      position: relative;
      padding-left: 20px;
   }
   .admin-silaris .menu-tree-card .dd-list .dd-list::before {
      position: absolute;
      top: -5px;
      bottom: 7px;
      left: 8px;
      width: 1px;
      background: #e1e6ee;
      content: "";
   }
   .admin-silaris .menu-tree-card .dd-item { min-height: 50px; }
   .admin-silaris .menu-tree-card .dd3-content {
      min-height: 44px !important;
      height: 44px !important;
      margin: 5px 0 !important;
      padding: 0 12px 0 48px !important;
      display: flex !important;
      align-items: center !important;
      box-sizing: border-box;
      border: 1px solid var(--border);
      border-radius: 8px;
      background: #fff;
      color: var(--ink-700);
      font-size: 12px;
      font-weight: 600;
      line-height: 20px;
      box-shadow: 0 1px 2px rgba(15, 27, 45, .025);
      transition: border-color .15s ease, background-color .15s ease, box-shadow .15s ease;
   }
   .admin-silaris .menu-tree-card .dd3-content:hover {
      border-color: #bac4d2;
      background: #fbfcfe;
      box-shadow: 0 3px 10px rgba(15, 27, 45, .055);
   }
   .admin-silaris .menu-tree-card .dd3-handle {
      left: 6px;
      top: 6px;
      z-index: 2;
      width: 32px;
      min-height: 32px !important;
      height: 32px !important;
      margin: 0 !important;
      padding: 0 !important;
      display: grid !important;
      place-items: center;
      overflow: hidden;
      box-sizing: border-box;
      border: 1px solid #e1e6ee;
      border-radius: 6px;
      background: #f5f7fa;
   }
   .admin-silaris .menu-tree-card .dd3-handle::before {
      position: static;
      display: block;
      width: auto;
      color: #9ca8b8;
      font-family: FontAwesome;
      font-size: 14px;
      line-height: 1;
      text-align: center;
      text-indent: 0;
      content: "\f0c9";
   }
   .admin-silaris .menu-tree-card .dd3-handle:hover { background: var(--accent-tint); }
   .admin-silaris .menu-tree-card .dd-item > button {
      position: absolute;
      top: 11px;
      left: 9px;
      z-index: 4;
      width: 26px;
      height: 22px;
      margin: 0;
      color: var(--ink-500);
   }
   .admin-silaris .menu-tree-card .dd3-item:has(> button) > .dd3-handle::before { display: none; }
   .admin-silaris .menu-tree-card .dd3-content .pull-right { margin-left: 6px; }
   .admin-silaris .menu-tree-card .dd3-content > .pull-right:first-of-type { margin-left: auto; }
   .admin-silaris .menu-tree-card .btn-action {
      display: inline-grid;
      width: 28px;
      height: 28px;
      place-items: center;
      border-radius: 6px;
      color: var(--brand);
   }
   .admin-silaris .menu-tree-card .btn-action:hover { background: var(--brand-tint); }
   .admin-silaris .menu-tree-card .fa-trash.btn-action { color: var(--danger); }
   .admin-silaris .menu-tree-card .dd-label {
      padding-left: 14px !important;
      border-color: #d8deea;
      background: #f7f8fb;
      color: var(--brand);
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .025em;
      text-transform: uppercase;
   }
   .admin-silaris .menu-tree-card .menu-toggle-activate_inactive > .dd3-content {
      border-color: #efc9cd;
      background: var(--danger-tint);
      color: #8f3038;
      opacity: .82;
   }
   @media (max-width: 767.98px) {
      .admin-silaris .menu-builder-page { padding: 0 !important; }
      .admin-silaris .menu-builder-page-header { align-items: flex-start; flex-direction: column; }
      .admin-silaris .menu-builder-shortcuts { display: none; }
      .admin-silaris .menu-builder-card .box-body { padding: 15px !important; }
      .admin-silaris .menu-tree-card .dd-list .dd-list { padding-left: 14px; }
      .admin-silaris .menu-tree-card .dd-list .dd-list::before { left: 5px; }
      .admin-silaris .menu-tree-card .dd3-content { padding: 0 10px 0 46px !important; }
   }
</style>
<script src="<?= BASE_ASSET; ?>m-switch/js/jquery.mswitch.js" type="text/javascript"></script>
<script type="text/javascript">
   //This page is a result of an autogenerated content made by running test.html with firefox.
   function domo(){
    
      $('*').bind('keydown', 'Ctrl+a', function assets() {
          window.location.href = BASE_URL + '/administrator/menu/add/' + '<?= $this->uri->segment(4); ?>';
          return false;
      });

      $('*').bind('keydown', 'Ctrl+r', function assets() {
          window.location.href = BASE_URL + '/administrator/menu_type/add/';
          return false;
      });
   
      $('*').bind('keydown', 'Ctrl+s', function assets() {
          $('#btn_save').trigger('click');
          return false;
      });
   
   }
   
   jQuery(document).ready(domo);
</script>

<!-- Main content -->
<section class="content menu-builder-page">
   <div class="menu-builder-container">
      <div class="menu-builder-page-header">
         <div>
            <span class="menu-builder-eyebrow">Konfigurasi Navigasi</span>
            <h1>Pengelola Menu</h1>
            <p>Susun struktur navigasi aplikasi dengan menarik dan meletakkan item menu.</p>
         </div>
         <div class="menu-builder-shortcuts"><kbd>Ctrl+A</kbd> Menu baru <kbd>Ctrl+R</kbd> Jenis menu</div>
      </div>
   <div class="row menu-builder-grid" >
      <div class="col-md-4">
         <div class="box box-warning menu-builder-card menu-type-card">
           <div class="box-header with-border">
                 <h3 class="box-title ">Jenis Menu</h3>
           </div>
            <div class="box-body ">
               <!-- Widget: user widget style 1 -->
               <div class="menu-type-wrapper <?= $this->uri->segment(4) == 'side-menu' ? 'active' :''; ?>">
               <div data-href="<?= site_url('administrator/menu/index/'.url_title('side menu')); ?>" class="clickable btn-block menu-type btn-group "> <?= cclang('side_menu'); ?>
               </div>
                <span class="menu-type-action menu-type-action--empty" aria-hidden="true"></span>
               </div>
               <?php foreach (db_get_all_data('menu_type', 'name!= "side menu"') as $row): ?>
               <div class="menu-type-wrapper  <?= $this->uri->segment(4) == url_title($row->name) ? 'active' :''; ?>">
                 <span data-href="<?= site_url('administrator/menu/index/'.url_title($row->name)); ?>" class="clickable btn-block menu-type btn-group">
                    <?= _ent(ucwords($row->name)); ?>
                  
                 </span>
                 <a class="menu-type-action remove-data" data-href="<?= base_url('administrator/menu_type/delete/'.$row->id); ?>" href="javascript:void()">
                     <i class="fa fa-trash"></i>
                 </a>
               </div>
               <?php endforeach; ?> 
               <a href="<?= site_url('administrator/menu_type/add'); ?>" class="btn btn-block btn-add btn-add-menu btn-flat" title="add menu type (Ctrl+r)"><i class="fa fa-plus-square-o"></i> <?= cclang('add_menu_type'); ?></a>
            </div>
            <!--/box body -->
         </div>
         <!--/box -->
      </div>
      <div class="col-md-8">
         <div class="box box-warning menu-builder-card menu-tree-card">
              <!-- Widget: user widget style 1 -->
             <div class="box-header with-border">
                  
                   <h3 class="box-title pull-left"><?= cclang('menu') ?> <?= ucwords(str_replace('-', ' ', $this->uri->segment(4) ?? '')); ?></h3>
             </div>
            <div class="box-body ">
               <div class="message">
                <div class="callout callout-info btn-flat menu-builder-tip">
                  <i class="fa fa-info-circle"></i> Klik dua kali pada item untuk mengaktifkan atau menonaktifkan menu.
                </div>
               </div>
               <!-- Widget: user widget style 1 -->
               <div class="menu-builder-toolbar">
                <?php is_allowed('menu_add', function(){?>
                <a class="btn btn-flat btn-default btn_add_new" id="btn_add_new" title="add new menu (Ctrl+a)" href="<?= site_url('administrator/menu/add/'. $this->uri->segment(4)); ?>"><i class="fa fa-plus-square-o" ></i>  <?= cclang('add_new_button', cclang('menu')); ?></a>
                <?php }) ?>
                <span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg"> <i><?= cclang('loading_saving_data'); ?></i></span>
             </div>
              <div class="dd" id="nestable">
                 <?php
                  $menu = display_menu_module(0, 1, $this->uri->segment(4), true); 
                  if (empty($menu)): ?>
                  <div class="box-no-data">No data menu</div>
                 <?php else: 
                 echo $menu;
                  endif; ?>
              </div>
              <div class="nestable-output"></div>
               </div>
              
            </div>
            <!--/box body -->
         </div>
      </div>
   </div>
   </div>
</section>

<script src="<?= BASE_ASSET; ?>nestable/jquery.nestable.js"></script>
<script>
$(document).ready(function() {
    $('.remove-data').click(function() {
        var url = $(this).attr('data-href');
        swal({
                title: "Are you sure?",
                text: "data to be deleted can not be restored!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    document.location.href = url;
                }
            });

        return false;
    }); /*end remove data click*/

    var timeout;
    $('.dd').on('change', function() {
        clearTimeout(timeout);
        timeout = setTimeout(updateOrderMenu, 2000);
    });

    function updateOrderMenu(ignoreMessage) {
            $('.loading').removeClass('loading-hide');
            var shownotif = true;
            var menu = $('.dd').nestable('serialize');

            if (typeof shownotif == 'undefined') {
                var shownotif = true;
            }


            if (typeof ignoreMessage == 'undefined') {
                var ignoreMessage = false;
            }

            $.ajax({
                    url: BASE_URL + 'administrator/menu/save_ordering',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        'menu': menu,
                        '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
                    },
                })
                .done(function(res) {
                    if (res.success) {
                        $('.sidebar-menu').html(res.menu);
                        if (shownotif) {
                            if (!ignoreMessage) {
                              toastr['success'](res.message);
                            }
                        }
                    } else {
                        if (shownotif) {
                            if (!ignoreMessage) {
                              toastr['warning'](res.message);
                            }
                        }
                    }
                })
                .fail(function() {
                    if (!ignoreMessage) {
                      toastr['warning']('Error save data please try again later');
                    }
                })
                .always(function() {
                    $('.loading').addClass('loading-hide');
                });
        }
        // activate Nestable for list 1
    $('#nestable').nestable({
        group: 1
    });


    $('.clickable').on('click', function() {
        var href = $(this).attr('data-href');

        window.location.href = href;

        return false;
    }); /*end clickable click*/

     $(".m_switch_check:checkbox").mSwitch({
          onRender:function(elem){
              changeSharingDashboard(elem.val(), 'dont_update');
              if (elem.val() == 0){
                  $.mSwitch.turnOff(elem);
              }else{
                  $.mSwitch.turnOn(elem);
              }
          },
          onTurnOn:function(elem){
             changeSharingDashboard(1, 'update');
          },
          onTurnOff:function(elem){
             changeSharingDashboard(0, 'update');
          }
      });



      function setMenuActive(id, status) {
        var data = [];

         data.push({
            name: csrf,
            value: token
        });
        data.push({
            name: 'status',
            value: status
        });
        data.push({
            name: 'id',
            value: id
        });

        $.ajax({
                url: BASE_URL + '/administrator/menu/set_status',
                type: 'POST',
                dataType: 'JSON',
                data: data,
            })
            .done(function(data) {
                if (data.success) {
                    toastr['success'](data.message);
                    updateOrderMenu(true)
                } else {
                    toastr['warning'](data.message);
                }

            })
            .fail(function() {
                toastr['error']('Error update status');
            });
      }


      $('.menu-toggle-activate').dblclick(function(event) {
        event.stopPropagation();
        var status = $(this).data('status');
        var id = $(this).data('id');

        switch (status) {
          case undefined : case 0 :
          $(this).removeClass('menu-toggle-activate_inactive');
          $(this).data('status', 1)
          setMenuActive(id,  1);
          break;
          case 1 :
          $(this).addClass('menu-toggle-activate_inactive');
          $(this).data('status', 0)
          setMenuActive(id,  0);
          break;
        }
      });

}); /*end doc ready*/
</script>
