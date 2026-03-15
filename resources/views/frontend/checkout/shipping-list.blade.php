<div class="shipping-list">
  <hr>
  <h2 class="payment-title mb-3">Select Shipping method</h2>
  @foreach($ships as $index => $ship)
  <div class="form-check mb-3">
    <input class="form-check-input" type="radio" name="ship" id="ship-{{ $index }}" value="{{ $ship['Rate'].'__'.$ship['MailService'] }}" {{ $index == 0 ? 'checked' : '' }}>
    <label class="form-check-label" for="ship-{{ $index }}">
      <b>{{ render_price($ship['Rate']) }}</b>
      <div>{!! htmlspecialchars_decode($ship['MailService']) !!}</div>
    </label>
  </div>
  @endforeach
  
</div>