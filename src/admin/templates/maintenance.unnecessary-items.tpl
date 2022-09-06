{if count($existingUnnecessaryItems) > 0}
<fieldset>
	<legend>{lng p="ufaf_title"}</legend>

  {lng p="ufaf_list_description"}
	
  <ul>
    {foreach $existingUnnecessaryItems item=path}
      <li>{$path}</li>
    {/foreach}
  </ul>

  {lng p="ufaf_description"}

  <br />

  <img src="{$tpldir}images/warning.png" border="0" alt="" width="16" height="16" align="absmiddle" />
  {lng p="ufaf_warning"}

  <div style="float:right;">
    <button class="button" type="button" onclick="runCleanUp()">{lng p="ufaf_action"}</button>
  </div>

</fieldset>
{else}
  {lng p="ufaf_nothing_to_do"}
{/if}

<script>
  function runCleanUp() {
    if (window.confirm('{lng p="ufaf_confirm"}')) {
      window.location.href = 'maintenance.php?action=unnecessaryFiles&do=exec&sid=' + currentSID
    }
  }
</script>
