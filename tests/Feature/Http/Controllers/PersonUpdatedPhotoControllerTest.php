<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Eloquent\Photo as PhotoEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage as StorageFacade;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PersonUpdatedPhotoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('update200Provider')]
    public function test_update200(?array $photo, callable $beforeExpected, callable $expected): void
    {
        $disk = StorageFacade::fake('public');
        $this->seedStorage($disk);
        $this->setConfigFakeDisk();

        $personId = 2;

        $beforeExpected($this, $disk, $personId);

        $response = $this->putJson(
            route('person.update', ['person' => $personId]),
            [
                'id' => $personId,
                'is_unavailable' => true,
                'is_live' => false,
                'gender' => 2,
                'photo' => $photo,
            ],
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(200);
        $expected($this, $disk, $personId);
    }

    /**
     * @return array[]
     */
    public static function update200Provider(): array
    {
        return [
            [
                null,
                function ($object, $disk, $personId) {
                    $photo = PhotoEloquentModel::where('person_id', $personId)->orderBy('order')->get();
                    $object->assertCount(1, $photo);
                    $object->assertFileExists($disk->path("photo/$personId/3.webp"));
                },
                function ($object, $disk, $personId) {
                    $photo = PhotoEloquentModel::where('person_id', $personId)->orderBy('order')->get();
                    $object->assertCount(0, $photo);
                    $object->assertFileDoesNotExist($disk->path("photo/$personId/3.webp"));
                },
            ],
            [
                [

                ],
                function ($object, $disk, $personId) {
                    $photo = PhotoEloquentModel::where('person_id', $personId)->orderBy('order')->get();
                    $object->assertCount(1, $photo);
                    $object->assertFileExists($disk->path("photo/$personId/3.webp"));
                },
                function ($object, $disk, $personId) {
                    $photo = PhotoEloquentModel::where('person_id', $personId)->orderBy('order')->get();
                    $object->assertCount(0, $photo);
                    $object->assertFileDoesNotExist($disk->path("photo/$personId/3.webp"));
                },
            ],
            [
                [
                    [
                        'order' => 1,
                        'file_name' => 'temp.png',
                    ],
                    [
                        'order' => 2,
                        'date' => '20??-01-13',
                        'file_name' => 'fake2.png',
                    ],
                    [
                        'order' => 3,
                        'date' => '2024-08-22',
                        'file_name' => 'fake1.png',
                    ],
                ],
                function ($object, $disk, $personId) {
                    $photo = PhotoEloquentModel::where('person_id', $personId)->orderBy('order')->get();
                    $object->assertCount(1, $photo);
                    $object->assertFileExists($disk->path("photo/$personId/3.webp"));
                },
                function ($object, $disk, $personId) {
                    $photo = PhotoEloquentModel::where('person_id', $personId)->orderBy('order')->get();
                    $object->assertCount(3, $photo);
                    $object->assertCount(3, $disk->allFiles("photo/$personId"));
                    $object->assertEquals('webp', (new \SplFileInfo($disk->allFiles("photo/$personId")[0]))->getExtension());
                    $object->assertEquals('webp', (new \SplFileInfo($disk->allFiles("photo/$personId")[1]))->getExtension());
                    $object->assertEquals('webp', (new \SplFileInfo($disk->allFiles("photo/$personId")[2]))->getExtension());

                    $object->assertEquals(1, $photo[0]->order);
                    $object->assertEquals(null, $photo[0]->date);

                    $object->assertEquals(2, $photo[1]->order);
                    $object->assertEquals('20??-01-13', $photo[1]->date);

                    $object->assertEquals(3, $photo[2]->order);
                    $object->assertEquals('2024-08-22', $photo[2]->date);

                    $object->assertFileDoesNotExist($disk->path("photo/$personId/3.webp"));
                },
            ],
        ];
    }
}
