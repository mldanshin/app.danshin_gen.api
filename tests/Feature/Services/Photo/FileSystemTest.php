<?php

namespace Tests\Feature\Services\Photo;

use App\Models\Download\Photo\FileArchive;
use App\Services\Photo\FileSystem as PhotoFileSystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

final class FileSystemTest extends TestCase
{
    public function test_create_object(): PhotoFileSystem
    {
        $disk = Storage::fake('public');

        $fileSystem = new PhotoFileSystem($disk);
        $this->assertInstanceOf(PhotoFileSystem::class, $fileSystem);

        return $fileSystem;
    }

    #[Depends('test_create_object')]
    #[DataProvider('getPathProvider')]
    public function test_get_path_relative(
        string $id,
        string $fileName,
        string $expected,
        PhotoFileSystem $fileSystem
    ): void {
        $this->assertEquals("photo/$expected", $fileSystem->getPathRelative($id, $fileName));
    }

    #[Depends('test_create_object')]
    #[DataProvider('getPathProvider')]
    public function test_get_path(
        string $id,
        string $fileName,
        string $expected,
        PhotoFileSystem $fileSystem
    ): void {
        $expected = Storage::fake('public')->path('photo/').$expected;
        $this->assertEquals($expected, $fileSystem->getPath($id, $fileName));
    }

    /**
     * @return array[]
     */
    public static function getPathProvider(): array
    {
        return [
            ['1', '1.txt', '1/1.txt'],
            ['4', '2.txt', '4/2.txt'],
            ['150', '345.txt', '150/345.txt'],
        ];
    }

    #[Depends('test_create_object')]
    #[DataProvider('getUrlProvider')]
    public function test_get_url(
        string $id,
        string $fileName,
        string $expected,
        PhotoFileSystem $fileSystem
    ): void {
        $expected = Storage::fake('public')->url('photo/').$expected;
        $this->assertEquals($expected, $fileSystem->getUrl($id, $fileName));
    }

    /**
     * @return array[]
     */
    public static function getUrlProvider(): array
    {
        return [
            ['1', '1.txt', '1/1.txt'],
            ['4', '2.txt', '4/2.txt'],
            ['150', '345.txt', '150/345.txt'],
        ];
    }

    #[Depends('test_create_object')]
    #[DataProvider('getPathTempProvider')]
    public function test_get_path_temp_relative(
        string $fileName,
        string $expected,
        PhotoFileSystem $fileSystem
    ): void {
        $this->assertEquals("photo_temp/$expected", $fileSystem->getPathTempRelative($fileName));
    }

    #[Depends('test_create_object')]
    #[DataProvider('getPathTempProvider')]
    public function test_get_path_temp(
        string $fileName,
        string $expected,
        PhotoFileSystem $fileSystem
    ): void {
        $expected = Storage::fake('public')->path('photo_temp/').$expected;
        $this->assertEquals($expected, $fileSystem->getPathTemp($fileName));
    }

    /**
     * @return array[]
     */
    public static function getPathTempProvider(): array
    {
        return [
            ['1.txt', '1.txt'],
            ['2.txt', '2.txt'],
            ['345.txt', '345.txt'],
        ];
    }

    #[Depends('test_create_object')]
    public function test_put(PhotoFileSystem $fileSystem): void
    {
        $this->seedStorage($fileSystem->disk);
        $file = new UploadedFile($fileSystem->disk->path('test.png'), 'test.png');

        // testing
        $personId = 1;
        $fileSystem->put($personId, $file);
        $this->assertFileExists($fileSystem->getPath($personId, $file->hashName()));
    }

    #[Depends('test_create_object')]
    public function test_put_temp(PhotoFileSystem $fileSystem): void
    {
        $this->seedStorage($fileSystem->disk);
        $file = new UploadedFile($fileSystem->disk->path('test.png'), 'test.png');

        // testing
        $fileSystem->putTemp($file);
        $this->assertFileExists($fileSystem->getPathTemp($file->hashName()));
    }

    #[Depends('test_create_object')]
    public function test_move_temp_success(PhotoFileSystem $fileSystem): void
    {
        $this->seedStorage($fileSystem->disk);

        $fileTemp = 'temp.png';
        $pathTemp = "photo_temp/$fileTemp";
        $personId = 1;

        $fileSystem->moveTemp($personId, collect([$fileTemp]));
        $this->assertFalse($fileSystem->disk->exists($pathTemp));
        $this->assertTrue($fileSystem->disk->exists("photo/$personId/$fileTemp"));
    }

    #[Depends('test_create_object')]
    public function test_move_temp_success_create_person(PhotoFileSystem $fileSystem): void
    {
        $this->seedStorage($fileSystem->disk);

        $fileTemp = 'temp.png';
        $pathTemp = "photo_temp/$fileTemp";
        $personId = 5;

        $fileSystem->moveTemp($personId, collect([$fileTemp]));
        $this->assertFalse($fileSystem->disk->exists($pathTemp));
        $this->assertTrue($fileSystem->disk->exists("photo/$personId/$fileTemp"));
    }

    #[Depends('test_create_object')]
    public function test_move_temp_wrong_not_file(PhotoFileSystem $fileSystem): void
    {
        $this->seedStorage($fileSystem->disk);

        $fileTemp = 'temp1.png';
        $pathTemp = "photo_temp/$fileTemp";
        $personId = 1;

        $this->expectException(\Exception::class);

        $fileSystem->moveTemp($personId, collect([$fileTemp]));
    }

    #[Depends('test_create_object')]
    public function test_get_path_directory_temp(PhotoFileSystem $fileSystem): void
    {
        $expected = Storage::fake('public')->path('photo_temp').'/';
        $this->assertEquals($expected, $fileSystem->getPathDirectoryTemp());
    }

    #[Depends('test_create_object')]
    public function test_exists_file_success(PhotoFileSystem $fileSystem): void
    {
        $this->seedStorage($fileSystem->disk);

        $this->assertTrue(
            $fileSystem->existsFile($fileSystem->disk->path('photo/1/1.webp'))
        );
    }

    #[Depends('test_create_object')]
    public function test_exists_file_wrong(PhotoFileSystem $fileSystem): void
    {
        $this->expectException(\Exception::class);

        $fileSystem->existsFile($fileSystem->disk->path('photo/1/fake.txt'));
    }

    #[Depends('test_create_object')]
    public function test_delete_person_success(PhotoFileSystem $fileSystem): void
    {
        $this->seedStorage($fileSystem->disk);

        $personId = 1;
        $path = $fileSystem->disk->path("photo/$personId");

        $this->assertTrue(File::exists($path));
        $this->assertTrue($fileSystem->deletePerson($personId));
        $this->assertFalse(File::exists($path));
    }

    #[Depends('test_create_object')]
    public function test_delete_person_files_success(PhotoFileSystem $fileSystem): void
    {
        $this->seedStorage($fileSystem->disk);

        $personId = 1;
        $pathDir = $fileSystem->disk->path("photo/$personId");
        $fileName1 = '1.webp';
        $fileName2 = '2.webp';
        $fileName3 = '3.webp';
        $pathFile1 = $pathDir.'/'.$fileName1;
        $pathFile2 = $pathDir.'/'.$fileName2;
        $pathFile3 = $pathDir.'/'.$fileName3;

        $this->assertTrue(File::exists($pathDir));
        $this->assertTrue(File::exists($pathFile1));
        $this->assertTrue(File::exists($pathFile2));
        $this->assertTrue(File::exists($pathFile3));

        $fileSystem->deletePersonFiles($personId, collect([$fileName1, $fileName2]));

        $this->assertFalse(File::exists($pathFile1));
        $this->assertFalse(File::exists($pathFile2));
        $this->assertTrue(File::exists($pathFile3));
        $this->assertTrue(File::exists($pathDir));
    }

    /**
     * @param  Collection<int, string>  $path
     * @param  Collection<int, string>  $expected
     */
    #[Depends('test_create_object')]
    #[DataProvider('getBaseNamesProvider')]
    public function test_get_base_names(Collection $path, Collection $expected, PhotoFileSystem $fileSystem): void
    {
        $this->assertEquals($expected, $fileSystem->getBaseNames($path));
    }

    /**
     * @return array[]
     */
    public static function getBaseNamesProvider(): array
    {
        return [
            [
                collect(['home/text.txt', 'https://danshin.net/hello/file.html']),
                collect(['text.txt', 'file.html']),
            ],
        ];
    }

    #[Depends('test_create_object')]
    #[DataProvider('getBaseNameProvider')]
    public function test_get_base_name(string $path, string $expected, PhotoFileSystem $fileSystem): void
    {
        $this->assertEquals($expected, $fileSystem->getBaseName($path));
    }

    /**
     * @return array[]
     */
    public static function getBaseNameProvider(): array
    {
        return [
            ['home/text.txt', 'text.txt'],
            ['https://danshin.net/hello/file.html', 'file.html'],
        ];
    }

    #[Depends('test_create_object')]
    public function test_get_files_archive(PhotoFileSystem $fileSystem): void
    {
        $this->seedStorage($fileSystem->disk);

        $filesArchive = $fileSystem->getFilesArchive();

        $this->assertCount(4, $filesArchive);
        $this->assertEquals(
            [
                new FileArchive($fileSystem->disk->path('photo/1/1.webp'), '1/1.webp'),
                new FileArchive($fileSystem->disk->path('photo/1/2.webp'), '1/2.webp'),
                new FileArchive($fileSystem->disk->path('photo/1/3.webp'), '1/3.webp'),
                new FileArchive($fileSystem->disk->path('photo/2/3.webp'), '2/3.webp'),
            ],
            $filesArchive
        );
    }
}
