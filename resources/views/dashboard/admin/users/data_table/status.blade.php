{{--claim--}}
<div class="form-group ml-3">
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="idle" value="{{ old('idle', $user->status) }}" {{ $user->idle == '1' ? 'checked' : '' }} disabled>
    </div>
</div>