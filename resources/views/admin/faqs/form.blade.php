<!-- _form.blade.php -->
<div class="mb-3">
    <label for="question">Question</label>
    <input type="text" name="question" class="form-control" value="{{ old('question', $faq->question ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="answer">Answer</label>
    <textarea name="answer" class="form-control editor" rows="5" required>{{ old('answer', $faq->answer ?? '') }}</textarea>
</div>
<div class="mb-3 form-check">
    <input type="checkbox" name="is_active" class="form-check-input" {{ old('is_active', $faq->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label">Active</label>
</div>
