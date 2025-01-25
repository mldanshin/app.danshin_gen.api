<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Eloquent\Activity as ActivityEloquentModel;
use App\Models\Eloquent\Email as EmailEloquentModel;
use App\Models\Eloquent\Internet as InternetEloquentModel;
use App\Models\Eloquent\Marriage as MarriageEloquentModel;
use App\Models\Eloquent\OldSurname as OldSurnameEloquentModel;
use App\Models\Eloquent\ParentChild as ParentChildEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Eloquent\Phone as PhoneEloquentModel;
use App\Models\Eloquent\Photo as PhotoEloquentModel;
use App\Models\Eloquent\Residence as ResidenceEloquentModel;
use App\Services\Photo\FileSystem as PhotoFileSystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage as StorageFacade;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PersonDeleteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('delete200Provider')]
    public function test_delete200(string $id, array $photo): void
    {
        $disk = StorageFacade::fake('public');
        $this->seedStorage($disk);

        $response = $this->deleteJson(
            route('person.destroy', ['person' => $id]),
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(200);

        $this->assertNull(PeopleEloquentModel::find($id));
        $this->assertCount(0, ActivityEloquentModel::where('person_id', $id)->get());
        $this->assertCount(0, EmailEloquentModel::where('person_id', $id)->get());
        $this->assertCount(0, InternetEloquentModel::where('person_id', $id)->get());
        $this->assertCount(0, MarriageEloquentModel::where('person1_id', $id)->get());
        $this->assertCount(0, MarriageEloquentModel::where('person2_id', $id)->get());
        $this->assertCount(0, OldSurnameEloquentModel::where('person_id', $id)->get());
        $this->assertCount(0, ParentChildEloquentModel::where('parent_id', $id)->get());
        $this->assertCount(0, ParentChildEloquentModel::where('child_id', $id)->get());
        $this->assertCount(0, PhoneEloquentModel::where('person_id', $id)->get());
        $this->assertCount(0, PhotoEloquentModel::where('person_id', $id)->get());
        $this->assertCount(0, ResidenceEloquentModel::where('person_id', $id)->get());

        $photoFileSystem = new PhotoFileSystem($disk);

        foreach ($photo as $item) {
            $this->assertFalse(
                File::exists($photoFileSystem->disk->path("photo/$id/$item"))
            );
        }
    }

    /**
     * @return array[]
     */
    public static function delete200Provider(): array
    {
        return [
            ['1', ['1.jpeg', '2.jpg', '3.png']],
            ['5', []],
        ];
    }

    public function test_delete401(): void
    {
        $response = $this->deleteJson(
            route('person.destroy', ['person' => '1'])
        );
        $response->assertStatus(401);
    }

    public function test_delete403(): void
    {
        $response = $this->deleteJson(
            route('person.destroy', ['person' => '1']),
            headers: $this->getHeaderUserToken()
        );
        $response->assertStatus(403);
    }

    #[DataProvider('delete404Provider')]
    public function test_delete404(string $id): void
    {
        $response = $this->deleteJson(
            route('person.destroy', ['person' => $id]),
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(404);
    }

    /**
     * @return array[]
     */
    public static function delete404Provider(): array
    {
        return [
            [
                '99999',
            ],
            [
                'fake',
            ],
        ];
    }
}
