<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * Original code based on the CommonMark JS reference parser (https://bitly.com/commonmark-js)
 *  - (c) John MacFarlane
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Inline\Parser;

use League\CommonMark\Inline\Element\Text;
use League\CommonMark\InlineParserContext;
use League\CommonMark\Util\Html5Entities;
use League\CommonMark\Util\RegexHelper;

final class EntityParser implements InlineParserInterface
{
    /**
     * @return string[]
     */
    public function getCharacters(): array
    {
        return ['&'];
    }

    /**
     * @param InlineParserContext $inlineContext
     *
     * @return bool
     */
    public function parse(InlineParserContext $inlineContext): bool
    {
        if ($m = $inlineContext->getCursor()->match('/^' . RegexHelper::PARTIAL_ENTITY . '/i')) {
            $inlineContext->getContainer()->appendChild(new Text(Html5Entities::decodeEntity($m)));

            return true;
        }

        return false;
    }
}
