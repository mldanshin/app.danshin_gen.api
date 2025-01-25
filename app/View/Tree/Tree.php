<?php

namespace App\View\Tree;

use App\Models\Tree\Family as FamilyModel;
use App\Models\Tree\Interactive as InteractiveModel;
use App\Models\Tree\PersonShort;
use App\Models\Tree\Tree as TreeModel;
use Illuminate\View\View;

final class Tree
{
    public readonly StylePerson $stylePerson;

    public readonly PersonShort $personTarget;

    public readonly Family $family;

    public readonly Size $size;

    public readonly View $view;

    public readonly string $content;

    public function __construct(
        private TreeModel $treeModel,
        ?InteractiveModel $interactive,
        private ?int $widthScreen = null,
        private ?int $heightScreen = null
    ) {
        $this->personTarget = $treeModel->personTarget;

        $this->initializeStylePerson(
            $widthScreen,
            $this->getLargeSize($treeModel->family, $interactive),
        );

        $this->family = new Family($treeModel->family, $this->stylePerson, $interactive);
        $this->family->setPoint(0, 0);

        $this->size = $this->family->size;

        $this->view = view(
            'tree.tree',
            ['tree' => $this]
        );

        $this->content = $this->view->render();
    }

    private function initializeStylePerson(?int $widthScreen, Size $preparatorySize): void
    {
        switch ($widthScreen) {
            case $preparatorySize->width <= $widthScreen:
                $this->stylePerson = $this->getStylePersonLarge();
                break;
            case $preparatorySize->width > ($widthScreen * 2):
                $this->stylePerson = $this->getStylePersonSmall();
                break;
            case $preparatorySize->width > $widthScreen:
                $this->stylePerson = $this->getStylePersonMiddle();
                break;
            default:
                $this->stylePerson = $this->getStylePersonLarge();
                break;
        }
    }

    private function getLargeSize(FamilyModel $model, ?InteractiveModel $interactive): Size
    {
        return (new Family($model, $this->getStylePersonLarge(), $interactive))->size;
    }

    private function getStylePersonLarge(): StylePerson
    {
        return new StylePerson(
            config('app.tree.style.person_lg.margine'),
            config('app.tree.style.person_lg.stroke_width'),
            config('app.tree.style.person_lg.padding'),
            config('app.tree.style.person_lg.font_size'),
            config('app.tree.style.person_lg.line_spacing'),
            new Size(
                config('app.tree.style.person_lg.button_width'),
                config('app.tree.style.person_lg.button_height')
            )
        );
    }

    private function getStylePersonMiddle(): StylePerson
    {
        return new StylePerson(
            config('app.tree.style.person_md.margine'),
            config('app.tree.style.person_md.stroke_width'),
            config('app.tree.style.person_md.padding'),
            config('app.tree.style.person_md.font_size'),
            config('app.tree.style.person_md.line_spacing'),
            new Size(
                config('app.tree.style.person_md.button_width'),
                config('app.tree.style.person_md.button_height')
            )
        );
    }

    private function getStylePersonSmall(): StylePerson
    {
        return new StylePerson(
            config('app.tree.style.person_sm.margine'),
            config('app.tree.style.person_sm.stroke_width'),
            config('app.tree.style.person_sm.padding'),
            config('app.tree.style.person_sm.font_size'),
            config('app.tree.style.person_sm.line_spacing'),
            new Size(
                config('app.tree.style.person_sm.button_width'),
                config('app.tree.style.person_sm.button_height')
            )
        );
    }
}
