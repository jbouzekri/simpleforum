<?php /* #?ini charset="utf-8"?

[TemplateSettings]
ExtensionAutoloadPath[]=simpleforum

[RegionalSettings]
TranslationExtensions[]=simpleforum

[SiteAccessSettings]
# Enable fetching topic in hidden forum node
ShowModeratedForumItems=true

[ForumSearchSettings]
# Define the search engine for forum entities
# 2 engines are available :
#   simpleForumSolr to use with ezfind. ezfind must be configured in multicore. See documentation
#   simpleForumSearch default search engine directly in current database
SearchEngine=simpleForumSolr

[Cache]
CacheItems[]=simpleforum

[Cache_simpleforum]
name=Simpleforum view cache
id=simpleforum
tags[]=content
tags[]=simpleforum
class=simpleForumCacheManager
purgeClass=simpleForumCacheManager

*/ ?>
