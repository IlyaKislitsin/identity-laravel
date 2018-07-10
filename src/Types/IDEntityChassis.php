<?php

namespace AvtoDev\IDEntity\Types;

use Exception;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\Helpers\Normalizer;
use AvtoDev\IDEntity\Helpers\Transliterator;

/**
 * Идентификатор - номер шасси.
 */
class IDEntityChassis extends AbstractTypedIDEntity
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return static::ID_TYPE_CHASSIS;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value)
    {
        try {
            // Заменяем множественные пробелы - одиночными
            $value = \preg_replace('~\s+~u', ' ', trim((string) $value));

            // Нормализуем символы дефиса
            $value = (string) Normalizer::normalizeDashChar($value);

            // Производим замену кириллических символов на латинские аналоги
            $value = Transliterator::transliterateString(Str::upper($value), true);

            // Удаляем все символы, кроме разрешенных
            $value = \preg_replace('~[^A-Z0-9\- ]~u', '', $value);

            // Заменяем множественные дефисы - одиночными
            $value = \preg_replace('~\-+~', '-', $value);

            return $value;
        } catch (Exception $e) {
            // Do nothing
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidateCallbacks()
    {
        return [
            function () {
                return $this->validateWithValidatorRule($this->getValue(), 'required|string|chassis_code');
            },
        ];
    }
}
