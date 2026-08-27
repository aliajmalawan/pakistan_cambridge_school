<?php $f = fn(array $a) => App\Core\View::partial('admin/partials/field', $a); ?>
<form method="post" enctype="multipart/form-data"
      action="<?= url($member ? '/admin/leadership/update/' . $member['id'] : '/admin/leadership/store') ?>"
      class="max-w-3xl bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6 md:p-8">
  <?= App\Core\Csrf::field() ?>
  <div class="grid sm:grid-cols-2 gap-5">
    <?php $f(['name' => 'name', 'label' => 'Full Name', 'required' => true, 'value' => old_raw('name', $member['name'] ?? ''), 'placeholder' => 'Prof. Syed Ali Raza']); ?>
    <?php $f(['name' => 'designation', 'label' => 'Designation', 'required' => true, 'value' => old_raw('designation', $member['designation'] ?? ''), 'placeholder' => 'Principal']); ?>
    <?php $f(['name' => 'qualification', 'label' => 'Qualification', 'value' => old_raw('qualification', $member['qualification'] ?? ''), 'placeholder' => 'M.Phil Physics — University of Peshawar']); ?>
    <?php $f(['name' => 'tenure', 'label' => 'Tenure', 'value' => old_raw('tenure', $member['tenure'] ?? ''), 'placeholder' => 'Principal since 2014']); ?>
    <?php $f(['name' => 'email', 'label' => 'Email (optional)', 'type' => 'email', 'value' => old_raw('email', $member['email'] ?? ''), 'placeholder' => 'principal@pakistancambridgeschool.edu.pk']); ?>
    <?php $f(['name' => 'sort_order', 'label' => 'Sort Order', 'type' => 'number', 'value' => old_raw('sort_order', (string) ($member['sort_order'] ?? '0')), 'help' => 'Lower numbers appear first.']); ?>

    <div class="sm:col-span-2">
      <?php $f(['name' => 'bio', 'label' => 'Short Bio', 'type' => 'textarea', 'rows' => 2, 'value' => old_raw('bio', $member['bio'] ?? ''), 'help' => 'One line shown on the profile card. Maximum 500 characters.']); ?>
    </div>

    <div class="sm:col-span-2">
      <?php $f(['name' => 'message', 'label' => 'Message / Statement', 'type' => 'textarea', 'rows' => 10, 'value' => old_raw('message', $member['message'] ?? ''), 'help' => 'Only needed for the featured profile. Wrap each paragraph in <p>…</p>.']); ?>
    </div>

    <div class="sm:col-span-2 rounded-lg border border-ink-100 bg-sunken/60 p-4">
      <?php $f(['name' => 'featured', 'label' => 'Feature this profile at the top of the page', 'type' => 'checkbox', 'checked' => (int) ($member['featured'] ?? 0) === 1]); ?>
      <p class="mt-2 text-xs text-ink-500">Only one profile can be featured. Ticking this box removes the feature from whoever holds it now.</p>
    </div>

    <?php $f(['name' => 'status', 'label' => 'Status', 'type' => 'select', 'value' => old_raw('status', $member['status'] ?? 'published'), 'options' => ['published' => 'Published', 'draft' => 'Draft']]); ?>

    <div class="sm:col-span-2">
      <?php $f(['name' => 'photo', 'label' => 'Photograph (optional)', 'type' => 'file', 'help' => 'Portrait orientation, at least 800×1000 for the featured profile; square works for the rest.']); ?>
      <?php if (!empty($member['photo'])): ?>
      <img src="<?= upload_url($member['photo']) ?>" alt="Current photograph" class="mt-3 h-32 w-24 rounded-lg object-cover">
      <?php endif; ?>
    </div>
  </div>

  <div class="mt-7 flex gap-3">
    <button type="submit" class="inline-flex items-center h-11 px-7 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition"><?= $member ? 'Save Changes' : 'Add Profile' ?></button>
    <a href="<?= url('/admin/leadership') ?>" class="inline-flex items-center h-11 px-6 rounded-lg text-ink-700 font-medium hover:bg-sunken transition">Cancel</a>
  </div>
</form>
<?php clear_old(); ?>
