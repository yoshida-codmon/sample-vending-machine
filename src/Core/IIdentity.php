<?php
declare(strict_types=1);

namespace Core;

use Ds\Hashable;

/**
 * エンティティを一意に特定する値
 * @template T
 */
interface IIdentity extends Hashable
{
    /**
     * このIDの値を取得する
     * @return T
     */
    public function getValue(): mixed;

    public function __toString(): string;
}
