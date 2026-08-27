<div class="flex items-center justify-between gap-4 mb-5">
  <p class="text-sm text-ink-500">Accounts with access to this admin panel. Only the super admin can manage users.</p>
  <a href="<?= url('/admin/users/create') ?>" class="inline-flex items-center h-10 px-5 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition shrink-0">+ Add User</a>
</div>

<div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm overflow-x-auto">
  <table class="w-full text-sm min-w-[640px]">
    <thead>
      <tr class="bg-sunken text-left text-[11px] uppercase tracking-wider text-ink-700">
        <th class="px-4 py-3 font-semibold">Name</th>
        <th class="px-4 py-3 font-semibold">Email</th>
        <th class="px-4 py-3 font-semibold">Role</th>
        <th class="px-4 py-3 font-semibold">Status</th>
        <th class="px-4 py-3 font-semibold">Last Login</th>
        <th class="px-4 py-3 font-semibold text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-ink-100">
      <?php $me = App\Core\Auth::id(); ?>
      <?php foreach ($users as $u): ?>
      <tr class="hover:bg-sunken/40">
        <td class="px-4 py-3 font-medium"><?= e($u['name']) ?><?= (int) $u['id'] === $me ? ' <span class="text-xs text-ink-500 font-normal">(you)</span>' : '' ?></td>
        <td class="px-4 py-3 text-ink-500"><?= e($u['email']) ?></td>
        <td class="px-4 py-3"><span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide <?= $u['role'] === 'superadmin' ? 'bg-gold/20 text-gold-deep' : 'bg-navy-50 text-navy' ?>"><?= e($u['role']) ?></span></td>
        <td class="px-4 py-3"><?= $u['status'] === 'active' ? '<span class="text-success font-semibold">Active</span>' : '<span class="text-ink-500">Disabled</span>' ?></td>
        <td class="px-4 py-3 text-ink-500 tabular-nums"><?= $u['last_login_at'] ? time_ago($u['last_login_at']) : 'Never' ?></td>
        <td class="px-4 py-3 text-right whitespace-nowrap">
          <a href="<?= url('/admin/users/edit/' . $u['id']) ?>" class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold text-navy hover:bg-navy-50 transition">Edit</a>
          <?php if ((int) $u['id'] !== $me): ?>
          <?php App\Core\View::partial('admin/partials/delete_button', ['action' => '/admin/users/delete/' . $u['id'], 'confirm' => 'Delete this admin account?']); ?>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
