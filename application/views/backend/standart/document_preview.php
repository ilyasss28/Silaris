<section class="content document-preview-page">
    <div class="document-preview-shell">
        <header class="document-preview-header">
            <div>
                <span class="document-preview-eyebrow">PRATINJAU DOKUMEN</span>
                <h1><?= _ent($document['title']); ?></h1>
                <p>Lihat isi dokumen terlebih dahulu sebelum mengunduhnya.</p>
            </div>
            <div class="document-preview-actions">
                <a href="<?= _ent($document['back_url']); ?>" class="btn document-btn document-btn-back">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
                <a href="<?= _ent($document['download_url']); ?>" class="btn document-btn document-btn-download">
                    <i class="fa fa-download"></i> Unduh
                </a>
            </div>
        </header>

        <div class="document-preview-meta">
            <div class="document-meta-main">
                <span class="document-type-badge"><?= _ent($document['extension']); ?></span>
                <div>
                    <strong title="<?= _ent($document['file_name']); ?>"><?= _ent($document['file_name']); ?></strong>
                    <small><?= _ent($document['owner']); ?><?= $document['date'] ? ' · ' . _ent($document['date']) : ''; ?></small>
                </div>
            </div>
            <a href="<?= _ent($document['open_url']); ?>" target="_blank" rel="noopener" class="document-open-tab">
                <i class="fa fa-external-link"></i> Buka tab baru
            </a>
        </div>

        <div class="document-preview-stage">
            <?php if ($document['preview_kind'] === 'image'): ?>
                <div class="document-image-wrap">
                    <img src="<?= _ent($document['file_url']); ?>" alt="Pratinjau <?= _ent($document['file_name']); ?>">
                </div>
            <?php elseif ($document['preview_kind'] === 'native'): ?>
                <iframe src="<?= _ent($document['file_url']); ?>" title="Pratinjau <?= _ent($document['file_name']); ?>"></iframe>
            <?php elseif ($document['preview_kind'] === 'office'): ?>
                <iframe src="<?= _ent($document['office_viewer_url']); ?>" title="Pratinjau <?= _ent($document['file_name']); ?>" referrerpolicy="no-referrer"></iframe>
            <?php else: ?>
                <div class="document-preview-empty">
                    <i class="fa fa-file-o"></i>
                    <h2>Format belum mendukung pratinjau</h2>
                    <p>Dokumen ini tetap aman dan dapat dibuka setelah diunduh.</p>
                    <a href="<?= _ent($document['download_url']); ?>" class="btn document-btn document-btn-download">
                        <i class="fa fa-download"></i> Unduh Dokumen
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.document-preview-page{padding:25px;background:#f3f6fb;min-height:calc(100vh - 184px)}
.document-preview-shell{overflow:hidden;border:1px solid #dfe5ef;border-radius:12px;background:#fff;box-shadow:0 10px 30px rgba(15,23,42,.06)}
.document-preview-header{display:flex;justify-content:space-between;align-items:center;gap:24px;padding:24px;border-bottom:1px solid #e3e8f1}
.document-preview-eyebrow{display:block;margin-bottom:5px;color:#aa7c00;font-size:11px;font-weight:800;letter-spacing:.08em}
.document-preview-header h1{margin:0;color:#07112d;font-size:25px;font-weight:800}
.document-preview-header p{margin:5px 0 0;color:#6c7890;font-size:13px}
.document-preview-actions{display:flex;flex-wrap:wrap;gap:9px}
.document-btn{display:inline-flex;min-height:40px;padding:9px 16px;align-items:center;justify-content:center;gap:8px;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none!important}
.document-btn-back{border:1px solid #d5ddea;background:#fff;color:#21304c}
.document-btn-download{border:1px solid #071058;background:#071058;color:#fff!important}
.document-preview-meta{display:flex;min-height:72px;padding:14px 20px;align-items:center;justify-content:space-between;gap:20px;background:#f8faff;border-bottom:1px solid #e3e8f1}
.document-meta-main{display:flex;min-width:0;align-items:center;gap:12px}
.document-type-badge{display:inline-flex;min-width:46px;height:34px;padding:0 9px;align-items:center;justify-content:center;border-radius:7px;background:#fff5c7;color:#896700;font-size:11px;font-weight:900}
.document-meta-main div{min-width:0}.document-meta-main strong{display:block;overflow:hidden;color:#111c38;font-size:13px;text-overflow:ellipsis;white-space:nowrap}.document-meta-main small{display:block;margin-top:4px;color:#748097;font-size:11px}
.document-open-tab{flex:0 0 auto;color:#34445f;font-size:12px;font-weight:700;text-decoration:none!important}
.document-preview-stage{height:calc(100vh - 360px);min-height:500px;padding:14px;background:#e8edf5}
.document-preview-stage iframe{width:100%;height:100%;border:0;border-radius:7px;background:#fff}
.document-image-wrap{height:100%;overflow:auto;text-align:center}.document-image-wrap img{max-width:100%;height:auto;border-radius:6px;background:#fff;box-shadow:0 4px 18px rgba(15,23,42,.12)}
.document-preview-empty{display:flex;height:100%;align-items:center;justify-content:center;flex-direction:column;text-align:center;background:#fff;border-radius:7px;color:#6c7890}.document-preview-empty>i{margin-bottom:14px;color:#d2a500;font-size:48px}.document-preview-empty h2{margin:0;color:#17223d;font-size:20px}.document-preview-empty p{margin:7px 0 18px}
@media(max-width:767px){.document-preview-page{padding:12px}.document-preview-header{padding:18px;align-items:flex-start;flex-direction:column}.document-preview-actions{width:100%}.document-preview-actions .document-btn{flex:1}.document-preview-meta{align-items:flex-start;flex-direction:column}.document-preview-stage{height:65vh;min-height:420px}}
</style>
