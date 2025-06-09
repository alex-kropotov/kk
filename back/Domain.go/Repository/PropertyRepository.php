<?php
declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entity\Property;
use Illuminate\Database\Eloquent\Collection;

class PropertyRepository
{
    public function findById(int $id): ?Property
    {
        return Property::with(['details', 'documents', 'owner', 'user'])->find($id);
    }

    public function findByCode(string $code): ?Property
    {
        return Property::where('code', $code)->first();
    }

    public function save(Property $property): bool
    {
        return $property->save();
    }

    public function delete(Property $property): bool
    {
        return $property->delete();
    }

    public function generateUniqueCode(): string
    {
        do {
            $code = 'P' . str_pad((string)random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        } while (Property::where('code', $code)->exists());

        return $code;
    }

    public function getCitiesWithCounts(): Collection
    {
        return Property::where('is_active', true)
            ->groupBy('city')
            ->selectRaw('city, COUNT(*) as count')
            ->orderBy('count', 'desc')
            ->get();
    }
}
