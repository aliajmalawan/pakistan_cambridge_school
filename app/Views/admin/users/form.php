<?php $f = fn(array $a) => App\Core\View::partial('admin/partials/field', $a); ?>
<form method="post"
      action="<?= url($user ? '/admin/users/update/' . $user['id'] : '/admin/users/store') ?>"
      class="max-w-2xl bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6 md:p-8">
  <?= App\Core\Csrf::field() ?>
  <div class="grid sm:grid-cols-2 gap-5">
    <?php $f(['name' => 'name', 'label' => 'Full Name', 'required' => true, 'value' => old_raw('name', $user['name'] ?? '')]); ?>
    <?php $f(['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'value' => old_raw('email', $user['email'] ?? '')]); ?>
    <?php $f(['name' => 'password', 'label' => $user ? 'New Password' : 'Password', 'type' => 'password', 'required' => !$user, 'help' => $user ? 'Leave blank to keep the current password' : 'Minimum 8 characters']); ?>
    <?php $f(['name' => 'role', 'label' => 'Role', 'type' => 'select', 'value' => old_raw('role', $user['role'] ?? 'editor'), 'options' => ['editor' => 'Editor — content only', 'admin' => 'Admin — content + inbox', 'superadmin' => 'Super Admin — full control'], 'help' => 'Role permissions are enforced in the panel']); ?>
    <?php $f(['name' => 'status', 'label' => 'Status', 'type' => 'select', 'value' => old_raw('status', $user['status'] ?? 'active'), 'options' => ['active' => 'Active', 'disabled' => 'Disabled — cannot sign in']]); ?>
  </div>
  <div class="mt-7 flex gap-3">
    <button type="submit" class="inline-flex items-center h-11 px-7 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition"><?= $user ? 'Save Changes' : 'Create User' ?></button>
    <a href="<?= url('/admin/users') ?>" class="inline-flex items-center h-11 px-6 rounded-lg text-ink-700 font-medium hover:bg-sunken transition">Cancel</a>
  </div>
</form>
<?php clear_old(); ?>
