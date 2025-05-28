<?php

/**
 * Trait BadWordsTraitModel
 *
 * for CModel instance
 */
trait BadWordsTraitModel
{
    public function i18nCheckDisabledWords($attribute)
    {
        if (!Yii::app()->user->checkAccess('backend_access') && !empty($allBadWords = Badwords::getAllBadWords())) {
            # $allWs = HFormEditor::getAllFields();

            $activeLangs = Lang::getActiveLangs(true);
            foreach ($activeLangs as $lang) {
                $attr = $attribute . '_' . $lang['name_iso'];

                if (!empty($stringWordsArr = $this->normalizeWords($this->$attr))) {
                    if (!empty($result = $this->searchIntersect($stringWordsArr, $allBadWords))) {
                        if (!$this->hasErrors($attr)/* && ($lang['name_iso'] == Yii::app()->language)*/) {
                            $this->addError(
                                $attr,
                                (count($activeLangs) > 1)
                                    ? Yii::t(
                                        'common',
                                        '{label} for {lang} contains forbidden words: {badwords}. You need to remove them.',
                                        array(
                                            '{label}' => $this->getAttributeLabel($attribute),
                                            '{lang}' => $lang['name'],
                                            '{badwords}' => implode(', ', $result)
                                        )
                                    )
                                    : Yii::t(
                                        'common',
                                        '{label} contains forbidden words: {badwords}. You need to remove them.',
                                        array(
                                            '{label}' => $this->getAttributeLabel($attribute),
                                            '{badwords}' => implode(', ', $result)
                                        )
                                    )
                            );
                        }
                    }
                }
            }
        }
    }

    public function checkDisabledWords($attribute)
    {
        if (!Yii::app()->user->checkAccess('backend_access') && !empty($allBadWords = Badwords::getAllBadWords())) {
            if (!empty($stringWordsArr = $this->normalizeWords($this->$attribute))) {
                if (!empty($result = $this->searchIntersect($stringWordsArr, $allBadWords))) {
                    if (!$this->hasErrors($attribute)/* && ($lang['name_iso'] == Yii::app()->language)*/) {
                        $this->addError(
                            $attribute,
                            Yii::t(
                                'common',
                                '{label} contains forbidden words: {badwords}. You need to remove them.',
                                array(
                                    '{label}' => $this->getAttributeLabel($attribute),
                                    '{badwords}' => implode(', ', $result)
                                )
                            )
                        );
                    }
                }
            }
        }
    }

    /**
     * @param string $str
     * @return array
     */
    protected function normalizeWords($str)
    {
        return array_map("trim", explode(' ', strip_tags($str)));
    }

    /**
     * @param array $needleList
     * @param array $haystackList
     * @return array
     */
    protected function searchIntersect(array $needleList, array $haystackList)
    {
        return array_uintersect($needleList, $haystackList, "customCompareFunction");
    }
}
