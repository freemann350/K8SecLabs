<script>
  function appendInput(baseDivName) {
    const baseDiv = document.getElementById(baseDivName);
    const baseInput = document.createElement('div');
    baseInput.classList.add('dynamic-input');

    if (baseDivName === 'string') {
      baseInput.innerHTML = `
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">String name</span>
        </div>
        <input type="text" class="form-control fix-height" name="str_name[]">
        <div class="input-group-prepend">
          <span class="input-group-text">Value</span>
        </div>
        <input type="text" class="form-control fix-height" name="str_val[]">
        <button type="button" class="btn btn-danger removeInput fix-height"><i class="ti-trash removeInput"></i></button>
      </div>
      `;
    }
    
    if (baseDivName === 'number') {
      baseInput.innerHTML = `
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">Number name</span>
        </div>
        <input type="text" class="form-control fix-height" name="num_name[]">
        <div class="input-group-prepend">
          <span class="input-group-text">Value</span>
        </div>
        <input type="text" class="form-control fix-height" name="num_val[]">
        <button type="button" class="btn btn-danger removeInput fix-height"><i class="ti-trash removeInput"></i></button>
      </div>
      `;
    }

    if (baseDivName === 'random') {
      baseInput.innerHTML = `
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">Rand. name</span>
        </div>
        <input type="text" class="form-control fix-height" name="rand_name[]">
        <div class="input-group-prepend">
          <span class="input-group-text">Min</span>
        </div>
        <input type="text" class="form-control fix-height" name="min[]">
        <div class="input-group-prepend">
          <span class="input-group-text">Max</span>
        </div>
        <input type="text" class="form-control fix-height" name="max[]">
        <button type="button" class="btn btn-danger removeInput fix-height"><i class="ti-trash removeInput"></i></button>
      </div>
      `;
    }

    if (baseDivName === 'flag') {
      baseInput.innerHTML = `
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">Flag name</span>
        </div>
        <input type="text" class="form-control fix-height" name="flag_name[]">
        <div class="input-group-prepend">
          <span class="input-group-text">Value</span>
        </div>
        <input type="text" class="form-control fix-height" name="flag_val[]" placeholder="Empty value creates a random sha256 flag (different each environment)">
        <button type="button" class="btn btn-danger removeInput fix-height"><i class="ti-trash removeInput"></i></button>
      </div>
      `;
    }
    baseDiv.appendChild(baseInput);
  }

  document.addEventListener('click', function(event) {
    if (event.target.classList.contains('removeInput')) {
      event.target.closest('.dynamic-input').remove();
    }
  });
</script>