<?php

namespace Codeia\Test;

/**
 * Description of VarAnnotations
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class VarAnnotations {
    /** '@var Codeia\Test\Emiya' */
    public $insideSingleQuotes;
    /** "@var Codeia\Test\Emiya" */
    public $insideDoubleQuotes;
    /** {@var Codeia\Test\Emiya} */
    public $insideBraces;
    /** \@var Codeia\Test\Emiya */
    public $atSignWasEscaped;
    /** \'@var Codeia\Test\Emiya */
    public $leftSingleQuoteWasEscaped;
    /** \"@var Codeia\Test\Emiya */
    public $leftDoubleQuoteWasEscaped;
    /** \{@var Codeia\Test\Emiya} */
    public $leftBraceWasEscaped;
    /** 'asdf' @var Codeia\Test\Emiya */
    public $afterSqString;
    /** "asdf" @var Codeia\Test\Emiya */
    public $afterDqString;
    /** {asdf} @var Codeia\Test\Emiya */
    public $afterBlock;
    /** {{level 2 {level 3 {{level 5 } level 4 }}} level 1} @var Codeia\Test\Emiya */
    public $afterNestedBlocks;
    /** {{{}} @var Codeia\Test\Emiya */
    public $afterImproperlyClosedBlocks;

    /** @var l!ul\4head */
    private $followedByAnInvalidFQCN;
}
