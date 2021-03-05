{*
* 2021 ARTULANCE.COM
*
* NOTICE OF LICENSE
*
* This file is licenced under the Software License Agreement.
* With the purchase or the installation of the software in your application
* you accept the licence agreement
*
* @author    ARTUROKI.TK <artudevweb@gmail.com>
* @copyright 2021 ARTULANCE.COM
* @license   Free license 
*
*}
<style>
{literal}

{/literal}
</style>


{block name='holi'}

<div id="holi_modulo_footer" class="container">
    <div class="py-5 text-center">
         <h2>{l s='Texto traducible del footer' mod='holi'}</h2>
        <p class="lead">{$texto_variable_footer|escape:'html':'UTF-8'}</p>
      </div>
</div>
{/block}
