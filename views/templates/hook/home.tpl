{*
* 2021 ARTULANCE.COM
*
* NOTICE OF LICENSE
*
* This file is licenced under the Software License Agreement.
* With the purchase or the installation of the software in your application
* you accept the licence agreement
*
* @author    ARTULANCE.COM <artudevweb@gmail.com>
* @copyright 2021 ARTULANCE.COM
* @license   Free license 
*
*}
<style>
{literal}
#holi_modulo,#holi_modulo_footer{
    border:2px dotted black;
}
{/literal}
</style>


{block name='holi'}
<div id="holi_modulo" class="container">
    <div class="py-5 text-center">
         <h2>{l s='Texto traducible' mod='holi'}</h2>
        <p class="lead">{$texto_variable|escape:'html':'UTF-8'}</p>
      </div>
</div>
{/block}
