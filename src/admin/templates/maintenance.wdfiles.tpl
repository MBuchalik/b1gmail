{if $numberOfWdFiles > 0}
<fieldset>
	<legend>{lng p="wdfiles_title"}</legend>

  {$description}

  <br />

  <div style="float:right;">
    <button class="button" type="button" onclick="runCleanUp()">{lng p="wdfiles_action"}</button>
  </div>

</fieldset>
{else}
  {lng p="wdfiles_nothing_to_do"}
{/if}

<script>
  function runCleanUp() {
    window.location.href = 'maintenance.php?action=wdfiles&do=exec&sid=' + currentSID
  }
</script>
