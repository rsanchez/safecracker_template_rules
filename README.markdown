# SafeCracker Template Rules #

Add form validation rules inside the template using template tags.

## Installation

* Copy the /system/expressionengine/third_party/safecracker_template_rules/ folder to your /system/expressionengine/third_party/ folder

## Usage

Add a field to your SafeCracker form, whose name is your SafeCracker File field name with _prefix at the end.

	{exp:safecracker channel="site" return="my/form/ENTRY_ID"}
		
		{set_rules}
		{rules:your_field_name="required"}
		{rules:your_other_field_name="required|max_length[10]"}
		{/set_rules}
		
	{/exp:safecracker}

You can also use conditionals inside the `{set_rules}` tag pair.

	{exp:safecracker channel="site" return="my/form/ENTRY_ID"}
		
		{set_rules}
		{if segment_1 == 'user_management'}
		{rules:your_field_name="required"}
		{/if}
		{rules:your_other_field_name="required|max_length[10]"}
		{/set_rules}
		
	{/exp:safecracker}