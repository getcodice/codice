<div class="quickform">
    <form action="{!! route('note.create') !!}" method="post">
        <div class="form-group">
            <label for="quickform_content" class="sr-only">@lang('note.labels.content')</label>
            <textarea name="content" id="quickform_content" rows="1" class="form-control" placeholder="@lang('note.quickform.content')" required></textarea>
        </div>
        <div class="row sr-only">
            <div class="form-group col-md-6">
                <label for="quickform_labels" class="sr-only">@lang('note.labels.labels')</label>
                <select name="labels[]" class="form-control" id="quickform_labels" multiple>
                @foreach ($quickform['labels'] as $id => $label)
                    <option value="{{ $id }}" {{ $quickform['label'] == $id ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="quickform_expires_at" class="sr-only">@lang('note.labels.expires_at')</label>
                <input type="text" name="expires_at" id="quickform_expires_at" class="form-control" placeholder="{{ datetime_placeholder('note.labels.expires_at') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary quickform-submit">@lang('note.create.submit')</button>
            </div>
        </div>
        {{ csrf_field() }}
        <input type="hidden" name="quickform_target" value="{!! $quickform['target_url'] !!}">
    </form>
</div>
