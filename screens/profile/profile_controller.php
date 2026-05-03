<?php
session_start();
?>

<script>
  function updateNameLive() {
    const firstName = document.getElementById('firstName').value;
    const lastName  = document.getElementById('lastName').value;
    const fullName  = (firstName + ' ' + lastName).trim() || 'First Name Last Name';
    const initial   = firstName.charAt(0).toUpperCase() || '?';

    document.getElementById('displayName').textContent   = fullName;
    document.getElementById('avatarDisplay').textContent = initial;
    document.getElementById('sidebarName').textContent   = fullName;
    document.getElementById('sidebarAvatar').textContent = initial;
  }

  function checkPass() {
    const np  = document.getElementById('newPassword').value;
    const cfp = document.getElementById('confirmPassword').value;
    const err = document.getElementById('passErr');

    if (cfp.length > 0 && np !== cfp) {
      document.getElementById('newPassword').classList.add('err');
      document.getElementById('confirmPassword').classList.add('err');
      err.classList.add('show');
    } else {
      document.getElementById('newPassword').classList.remove('err');
      document.getElementById('confirmPassword').classList.remove('err');
      err.classList.remove('show');
    }
  }

  function saveProfile() {
    const np  = document.getElementById('newPassword').value;
    const cfp = document.getElementById('confirmPassword').value;

    if (np !== '' && np !== cfp) {
      showToast('Passwords do not match!', true);
      return;
    }

    updateNameLive();
    showToast('Profile updated successfully!', false);
  }

  function resetForm() {
    document.getElementById('firstName').value       = '';
    document.getElementById('lastName').value        = '';
    document.getElementById('email').value           = '';
    document.getElementById('phone').value           = '';
    document.getElementById('address').value         = '';
    document.getElementById('currentPassword').value = '';
    document.getElementById('newPassword').value     = '';
    document.getElementById('confirmPassword').value = '';

    document.getElementById('newPassword').classList.remove('err');
    document.getElementById('confirmPassword').classList.remove('err');
    document.getElementById('passErr').classList.remove('show');

    document.getElementById('displayName').textContent   = 'First Name Last Name';
    document.getElementById('avatarDisplay').textContent = '?';
    document.getElementById('sidebarName').textContent   = 'First Name Last Name';
    document.getElementById('sidebarAvatar').textContent = '?';

    showToast('Changes cancelled~', false);
  }

  function showToast(msg, isErr) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'toast' + (isErr ? ' err-toast' : '');
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
  }
</script>