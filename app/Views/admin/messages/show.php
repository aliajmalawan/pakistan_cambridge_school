<div class="max-w-2xl">
  <a href="<?= url('/admin/messages') ?>" class="text-sm font-semibold text-navy hover:underline">← All messages</a>

  <div class="mt-4 bg-white rounded-xl border border-ink-100 shadow-kds-sm">
    <div class="px-6 py-5 border-b border-ink-100">
      <h2 class="font-heading font-bold text-xl text-navy"><?= e($message['subject']) ?></h2>
      <div class="text-sm text-ink-500 mt-1">
        From <span class="font-medium text-ink-700"><?= e($message['name']) ?></span>
        · <?= format_date($message['created_at'], 'j M Y, g:i A') ?>
      </div>
    </div>
    <div class="px-6 py-5 text-[15px] leading-relaxed text-ink-700 whitespace-pre-line"><?= e($message['message']) ?></div>
    <div class="px-6 py-5 border-t border-ink-100 flex items-center gap-3 flex-wrap">
      <?php if ($message['email']): ?>
      <a href="mailto:<?= e($message['email']) ?>?subject=Re: <?= e($message['subject']) ?>" class="h-10 px-5 inline-flex items-center rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition">Reply by Email</a>
      <?php endif; ?>
      <?php if ($message['phone']): ?>
      <a href="tel:<?= e(preg_replace('/\s+/', '', $message['phone'])) ?>" class="h-10 px-5 inline-flex items-center rounded-lg border border-ink-300 text-sm font-medium hover:bg-sunken transition">Call <?= e($message['phone']) ?></a>
      <?php endif; ?>
      <div class="ml-auto">
        <?php App\Core\View::partial('admin/partials/delete_button', ['action' => '/admin/messages/delete/' . $message['id'], 'label' => 'Delete Message']); ?>
      </div>
    </div>
  </div>
</div>
