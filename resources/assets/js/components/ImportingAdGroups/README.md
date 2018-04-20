# Importing Activity Directory Groups

Calls these API links:

	1. /active-directory/{id}/get-ad-groups 		[GET]
	2. 1. /active-directory/{id}/store-ad-groups 	[POST - requires a an array key with 'groups' and an array inside for each group]

Things to do:

	1. Error handling of API failure.
	2. If user doesn't select any AD Groups to be imported, allow/promt user to go back to the active directory OR roles pages.