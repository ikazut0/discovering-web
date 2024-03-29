<script>
  function alert(type, msg) {
    let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
    let element = document.createElement('div');
    element.innerHTML = `
    <div class="alert ${bs_class} alert-custom alert-dismissible fade show text-center position-fixed top-50 start-50 translate-middle" role="alert">
      <strong>${msg}</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    `;
    document.body.append(element);

    setTimeout(() => {
      element.querySelector('.btn-close').click();
    }, 2600);
  }
</script>