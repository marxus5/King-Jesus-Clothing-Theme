jQuery(function($){
  function updateButtons($field){
    var $input = $field.find('input.qty');
    var min = parseFloat($input.attr('min')) || 0;
    var max = parseFloat($input.attr('max')) || Infinity;
    var val = parseFloat($input.val()) || 0;
    $field.find('.qty-decrease').prop('disabled', val <= min);
    $field.find('.qty-increase').prop('disabled', val >= max);
  }

  // add buttons if not present
  $('.quantity').each(function(){
    var $q = $(this);
    if ($q.find('.qty-decrease').length) return;
    var $de = $('<button type="button" class="qty-button qty-decrease" aria-label="Decrease quantity">−</button>');
    var $in = $('<button type="button" class="qty-button qty-increase" aria-label="Increase quantity">+</button>');
    $q.prepend($de);
    $q.append($in);
    updateButtons($q);
  });

  // click handlers
  $(document).on('click', '.qty-decrease, .qty-increase', function(){
    var $btn = $(this);
    var $field = $btn.closest('.quantity');
    var $input = $field.find('input.qty');
    var step = parseFloat($input.attr('step')) || 1;
    var min = parseFloat($input.attr('min'));
    var max = parseFloat($input.attr('max'));
    var val = parseFloat($input.val()) || 0;
    if ($btn.hasClass('qty-decrease')) val = Math.max(min || 0, val - step);
    else val = (typeof max === 'number' && !isNaN(max)) ? Math.min(max, val + step) : val + step;
    $input.val(val).trigger('change');
    updateButtons($field);
  });

  // update buttons if input changed manually
  $(document).on('input change', '.quantity input.qty', function(){
    updateButtons($(this).closest('.quantity'));
  });
});