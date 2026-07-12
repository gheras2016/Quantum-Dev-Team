<?php

namespace App\Services\Admin;

use App\Models\Faq;
use App\Services\Admin\Concerns\FiltersResources;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class FaqService
{
    use FiltersResources;

    public function paginate(Request $request): LengthAwarePaginator
    {
        $query = Faq::query()->ordered();

        $this->applySearch($query, $request, ['question->en', 'question->ar']);
        $this->applyEquals($query, $request, ['is_active' => 'is_active']);

        return $query->paginate(15)->withQueryString();
    }

    public function create(array $data): Faq
    {
        return Faq::create($this->attributes($data));
    }

    public function update(Faq $faq, array $data): Faq
    {
        $faq->update($this->attributes($data));

        return $faq;
    }

    public function delete(Faq $faq): void
    {
        $faq->delete();
    }

    private function attributes(array $data): array
    {
        return [
            'question' => $data['question'],
            'answer' => $data['answer'],
            'order' => $data['order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ];
    }
}
