<?php /* expects: $status ('published'|'draft') */ ?>
<?php if ($status === 'published'): ?>
<span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-success/15 text-success">Published</span>
<?php else: ?>
<span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-ink-100 text-ink-500">Draft</span>
<?php endif; ?>
