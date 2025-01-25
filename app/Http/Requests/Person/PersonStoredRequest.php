<?php

namespace App\Http\Requests\Person;

use App\Http\Requests\Request;
use App\Models\Date;
use App\Models\Eloquent\MarriageRoleGender;
use App\Models\Eloquent\MarriageRoleScope;
use App\Models\Person\Editor\Stored\Internet;
use App\Models\Person\Editor\Stored\Marriage;
use App\Models\Person\Editor\Stored\OldSurname;
use App\Models\Person\Editor\Stored\ParentModel;
use App\Models\Person\Editor\Stored\Person;
use App\Models\Person\Editor\Stored\Photo;
use App\Models\Person\Editor\Stored\Residence;
use App\Repositories\Person\Editor\Marriage as MarriageRepository;
use App\Repositories\Person\Editor\Parents as ParentsRepository;
use App\Repositories\Person\Editor\Photo as PhotoRepository;
use App\Rules\BirthDate as BirthDateRule;
use App\Rules\DeathDate as DeathDateRule;
use App\Rules\IsLiveAndDeathDate as IsLiveAndDeathDateRule;
use App\Rules\Patronymic as PatronymicRule;
use App\Rules\Phone as PhoneRule;
use App\Rules\PhotoDate as PhotoDateRule;
use App\Rules\ResidenceDate as ResidenceDateRule;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

final class PersonStoredRequest extends Request
{
    use Phone;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'is_unavailable' => 'required|boolean',
            'is_live' => ['required', 'boolean', new IsLiveAndDeathDateRule],
            'gender' => 'required|integer|exists:genders,id',
            'surname' => 'nullable|string|max:255',
            'old_surname' => 'nullable|array',
            'old_surname.*.surname' => 'required_with:old_surname|string|max:255',
            'old_surname.*.order' => 'required_with:old_surname|integer|min:1|distinct',
            'name' => 'nullable|string|max:255',
            'patronymic' => ['nullable', 'string', new PatronymicRule, 'max:255'],
            'has_patronymic' => 'nullable|boolean',
            'birth_date' => ['nullable', 'string', new BirthDateRule],
            'birth_place' => 'nullable|string|max:255',
            'death_date' => ['nullable', 'string', new DeathDateRule],
            'burial_place' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'activities' => 'nullable|array',
            'activities.*' => 'required_with:activities|string|max:255|distinct',
            'emails' => 'nullable|array',
            'emails.*' => [
                'required_with:emails',
                'max:255',
                'email',
                'distinct',
                Rule::unique('emails', 'name'),
            ],
            'internet' => 'nullable|array',
            'internet.*.name' => 'required_with:internet|string|max:255|distinct',
            'internet.*.url' => 'required_with:internet|url|max:255|distinct',
            'phones' => 'nullable|array',
            'phones.*' => [
                'required_with:phones',
                'distinct',
                new PhoneRule,
                Rule::unique('phones', 'name'),
            ],
            'residences' => 'nullable|array',
            'residences.*.name' => 'required_with:residences|string|max:255',
            'residences.*.date' => ['nullable', 'string', 'distinct', new ResidenceDateRule],
            'parents' => 'nullable|array',
            'parents.*.person' => [
                'required_with:parents',
                'integer',
                'distinct',
                'exists:people,id',
                'different:marriages.*.soulmate',
            ],
            'parents.*.role' => [
                'required_with:parents',
                'integer',
                'exists:parent_roles,id',
            ],
            'marriages' => 'nullable|array',
            'marriages.*.role' => 'required_with:marriages|integer|exists:marriage_roles,id',
            'marriages.*.soulmate' => [
                'required_with:marriages',
                'integer',
                'exists:people,id',
                'different:parents.*.person',
            ],
            'marriages.*.soulmate_role' => 'required_with:marriages|integer|exists:marriage_roles,id',
            'photo' => 'nullable|array',
            'photo.*.order' => 'required_with:photo|integer|min:1|distinct',
            'photo.*.date' => [
                'nullable',
                new PhotoDateRule,
            ],
            'photo.*.file_name' => 'required_with:photo|string|distinct',
        ];
    }

    public function getModel(): ?Person
    {
        return new Person(
            $this->input('is_unavailable'),
            $this->input('is_live'),
            $this->input('gender'),
            $this->input('surname'),
            $this->getOldSurname(),
            $this->input('name'),
            $this->input('patronymic'),
            ($this->input('has_patronymic') === null) ? true : $this->input('has_patronymic'),
            $this->getDate($this->input('birth_date')),
            $this->input('birth_place'),
            $this->getDate($this->input('death_date')),
            $this->input('burial_place'),
            $this->input('note'),
            $this->getActivities(),
            $this->getEmails(),
            $this->getInternet(),
            $this->getPhones(),
            $this->getResidences(),
            $this->getParents(),
            $this->getMarriages(),
            $this->getPhoto()
        );
    }

    /**
     * @return array|callable[]
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $validated = $this->validated();

                if (isset($validated['parents'])) {
                    $parents = $this->input('parents');
                    $parentsRepository = new ParentsRepository;
                    $birthDate = $this->getDate($this->input('birth_date'));
                    $marriage = $this->getMarriagePeople();
                    foreach ($parents as $parent) {
                        if (isset($parent['role'])
                            && is_numeric($parent['role'])
                            && isset($parent['person'])
                            && is_numeric($parent['person'])
                        ) {
                            $possibles = $parentsRepository->getPossible(
                                null,
                                $birthDate,
                                $parent['role'],
                                $marriage
                            );
                            if ($possibles->search($parent['person']) === false) {
                                $validator->errors()->add(
                                    'parents',
                                    __('validation_app.parents.impossible', [
                                        'attribute' => $parent['person'],
                                    ])
                                );
                            }
                        }
                    }
                }
            },
            function (Validator $validator) {
                $validated = $this->validated();

                if (isset($validated['marriages'])) {
                    $marriages = $this->input('marriages');
                    $marriageRepository = new MarriageRepository;
                    $birthDate = $this->getDate($this->input('birth_date'));
                    $parents = $this->getParentsPeople();
                    foreach ($marriages as $marriage) {
                        if (isset($marriage['role'])
                            && is_numeric($marriage['role'])
                            && isset($marriage['soulmate_role'])
                            && is_numeric($marriage['soulmate_role'])
                            && isset($marriage['soulmate'])
                            && is_numeric($marriage['soulmate'])
                        ) {
                            $possibles = $marriageRepository->getPossible(
                                null,
                                $birthDate,
                                $marriage['role'],
                                $parents
                            );
                            if ($possibles->search($marriage['soulmate']) === false) {
                                $validator->errors()->add(
                                    'marriages',
                                    __('validation_app.marriages.person_impossible', [
                                        'attribute' => $marriage['soulmate'],
                                    ])
                                );
                            }

                            if (! MarriageRoleScope::where('role1_id', $marriage['role'])
                                ->where('role2_id', $marriage['soulmate_role'])->exists()
                            ) {
                                $validator->errors()->add(
                                    'marriages',
                                    __('validation_app.marriages.roles_impossible')
                                );
                            }

                            if ($this->input('gender') !== null
                                && ! MarriageRoleGender::where('gender_id', $this->input('gender'))
                                    ->where('role_id', $marriage['role'])->exists()
                            ) {
                                $validator->errors()->add(
                                    'marriages',
                                    __('validation_app.marriages.gender')
                                );
                            }
                        }
                    }
                }
            },
            function (Validator $validator) {
                $validated = $this->validated();

                if (isset($validated['photo'])) {
                    $photo = $this->input('photo');
                    $photoRepository = new PhotoRepository;
                    foreach ($photo as $item) {
                        if (! $photoRepository->existsTemp($item['file_name'])) {
                            $validator->errors()->add(
                                'photo',
                                __('validation_app.photo.file.exists')
                            );
                        }
                    }
                }
            },
        ];
    }

    /**
     * @return Collection<int, OldSurname>
     */
    private function getOldSurname(): Collection
    {
        $input = $this->input('old_surname');

        if ($input === null) {
            return collect();
        } else {
            $collect = collect();
            foreach ($input as $item) {
                $collect->push(
                    new OldSurname(
                        $item['surname'],
                        $item['order']
                    )
                );
            }

            return $collect;
        }
    }

    private function getDate(?string $date): ?Date
    {
        return Date::decode($date);
    }

    /**
     * @return Collection<int, string>
     */
    private function getActivities(): Collection
    {
        $input = $this->input('activities');

        if ($input === null) {
            return collect();
        } else {
            $collect = collect();
            foreach ($input as $item) {
                $collect->push(
                    $item
                );
            }

            return $collect;
        }
    }

    /**
     * @return Collection<int, string>
     */
    private function getEmails(): Collection
    {
        $input = $this->input('emails');

        if ($input === null) {
            return collect();
        } else {
            $collect = collect();
            foreach ($input as $item) {
                $collect->push(
                    $item
                );
            }

            return $collect;
        }
    }

    /**
     * @return Collection<int, Internet>
     */
    private function getInternet(): Collection
    {
        $input = $this->input('internet');

        if ($input === null) {
            return collect();
        } else {
            $collect = collect();
            foreach ($input as $item) {
                $collect->push(
                    new Internet(
                        $item['url'],
                        $item['name']
                    )
                );
            }

            return $collect;
        }
    }

    /**
     * @return Collection<int, string>
     */
    private function getPhones(): Collection
    {
        $input = $this->input('phones');

        if ($input === null) {
            return collect();
        } else {
            $collect = collect();
            foreach ($input as $item) {
                $collect->push(
                    $this->cleanPhone($item)
                );
            }

            return $collect;
        }
    }

    /**
     * @return Collection<int, Residence>
     */
    private function getResidences(): Collection
    {
        $input = $this->input('residences');

        if ($input === null) {
            return collect();
        } else {
            $collect = collect();
            foreach ($input as $item) {
                $collect->push(
                    new Residence(
                        $item['name'],
                        isset($item['date']) ? $this->getDate($item['date']) : null
                    )
                );
            }

            return $collect;
        }
    }

    /**
     * @return Collection<int, ParentModel>
     */
    private function getParents(): Collection
    {
        $input = $this->input('parents');

        if ($input === null) {
            return collect();
        } else {
            $collect = collect();
            foreach ($input as $item) {
                $collect->push(
                    new ParentModel(
                        $item['person'],
                        $item['role'],
                    )
                );
            }

            return $collect;
        }
    }

    /**
     * @return Collection<int, int>
     */
    private function getParentsPeople(): Collection
    {
        $input = $this->input('parents');

        if ($input === null) {
            return collect();
        } else {
            $collect = collect();
            foreach ($input as $item) {
                $collect->push(
                    $item['person']
                );
            }

            return $collect;
        }
    }

    /**
     * @return Collection<int, Marriage>
     */
    private function getMarriages(): Collection
    {
        $input = $this->input('marriages');

        if ($input === null) {
            return collect();
        } else {
            $collect = collect();
            foreach ($input as $item) {
                $collect->push(
                    new Marriage(
                        $item['role'],
                        $item['soulmate'],
                        $item['soulmate_role'],
                    )
                );
            }

            return $collect;
        }
    }

    /**
     * @return Collection<int, int>
     */
    private function getMarriagePeople(): Collection
    {
        $input = $this->input('marriages');

        if ($input === null) {
            return collect();
        } else {
            $collect = collect();
            foreach ($input as $item) {
                $collect->push(
                    $item['soulmate']
                );
            }

            return $collect;
        }
    }

    /**
     * @return Collection<int, Photo>
     */
    private function getPhoto(): Collection
    {
        $input = $this->input('photo');
        if (empty($input)) {
            return collect();
        } else {
            $collect = collect();
            $count = count($input);
            foreach ($input as $key => $value) {
                $collect->push(
                    new Photo(
                        $input[$key]['order'],
                        isset($input[$key]['date']) ? $this->getDate($input[$key]['date']) : null,
                        $input[$key]['file_name']
                    )
                );
            }

            return $collect;
        }
    }
}
