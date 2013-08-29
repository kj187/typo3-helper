<?php
namespace Aijko\Typo3Helper\Task\Renamer\Typo3;

/*                                                                        *
 * This script belongs to the aijko project autoinstaller app              *
 *                                                                        *
 *                                                                        */

use \Aijko\Typo3Helper\Service\RegistryService as Registry;
use \Gaufrette\Filesystem;
use \Gaufrette\Adapter\Local as LocalAdapter;

/**
 * Rename Content Task
 *
 * @author Julian Kleinhans <julian.kleinhans@aijko.de>
 * @copyright Copyright (c) 2013 aijko GmbH (http://www.aijko.de)
 */
class RenameContentTask extends \Aijko\Typo3Helper\Task\Renamer\RenameContentTask {

	/**
	 * TYPO3 6.0 Extension Migration
	 * http://wiki.typo3.org/TYPO3_6.0_Extension_Migration_Tips
	 *
	 * @param string $targetPath
	 * @param array $replacePatterns
	 * @return mixed|void
	 */
	public function execute($targetPath = '', array $replacePatterns = array()) {
		parent::execute(
			$targetPath,
			array(


			// Static calls
				"tx_em_Tools::getArrayFromLocallang" => "pageRenderer->addInlineLanguageLabelFile", // deprecated since TYPO3 4.5.1, will be removed in TYPO3 4.7 - use pageRenderer->addInlineLanguageLabelFile() instead
				"t3lib_BEfunc::searchQuery" => "\$GLOBALS['TYPO3_DB']->searchQuery", // deprecated since TYPO3 3.6, this function will be removed in TYPO3 4.6, use $GLOBALS['TYPO3_DB']->searchQuery() directly!
				"t3lib_BEfunc::listQuery" => "\$GLOBALS['TYPO3_DB']->listQuery", // deprecated since TYPO3 3.6, this function will be removed in TYPO3 4.6, use $GLOBALS['TYPO3_DB']->listQuery() directly!
				"t3lib_BEfunc::mm_query" => "\$GLOBALS['TYPO3_DB']->exec_SELECT_mm_query", // deprecated since TYPO3 3.6, this function will be removed in TYPO3 4.6, use $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query() instead since that will return the result pointer while this returns the query. Using this function may make your application less fitted for DBAL later.
				"t3lib_BEfunc::DBcompileInsert" => "\$GLOBALS['TYPO3_DB']->exec_INSERTquery", // deprecated since TYPO3 3.6, this function will be removed in TYPO3 4.6, use $GLOBALS['TYPO3_DB']->exec_INSERTquery() directly!
				"t3lib_BEfunc::DBcompileUpdate" => "\$GLOBALS['TYPO3_DB']->exec_UPDATEquery", // deprecated since TYPO3 3.6, this function will be removed in TYPO3 4.6, use $GLOBALS['TYPO3_DB']->exec_UPDATEquery() directly!
				#"t3lib_BEfunc::titleAttrib" => "", // deprecated since TYPO3 3.6, this function will be removed in TYPO3 4.6 - The idea made sense with older browsers, but now all browsers should support the title" attribute - so just hardcode the title attribute instead!"
				#"t3lib_BEfunc::getSetUpdateSignal" => "", // deprecated since TYPO3 4.2, this function will be removed in TYPO3 4.6, use the setUpdateSignal function instead, as it allows you to add more parameters
				#"t3lib_BEfunc::typo3PrintError" => "", // deprecated since TYPO3 4.5, will be removed in TYPO3 4.7 - use RuntimeException from now on
				#"t3lib_BEfunc::getListOfBackendModules" => "", // deprecated since TYPO3 3.6, this function will be removed in TYPO3 4.6.
				"t3lib_div::GPvar" => "t3lib_div::_GP", // deprecated since TYPO3 3.6, will be removed in TYPO3 4.6 - Use t3lib_div::_GP instead (ALWAYS delivers a value with un-escaped values!)
				"t3lib_div::GParrayMerged" => "t3lib_div::_GPmerged", // deprecated since TYPO3 3.7, will be removed in TYPO3 4.6 - Use t3lib_div::_GPmerged instead
				"t3lib_div::fixed_lgd" => "t3lib_div::fixed_lgd_cs", // deprecated since TYPO3 4.1, will be removed in TYPO3 4.6 - Works ONLY for single-byte charsets! Use t3lib_div::fixed_lgd_cs() instead
				"t3lib_div::fixed_lgd_pre" => "t3lib_div::fixed_lgd_cs", // deprecated since TYPO3 4.1, will be removed in TYPO3 4.6 - Use t3lib_div::fixed_lgd_cs() instead (with negative input value for $chars)
				"t3lib_div::breakTextForEmail" => "wordwrap", // deprecated since TYPO3 4.1, will be removed in TYPO3 4.6 - Use PHP function wordwrap()
				"t3lib_div::rm_endcomma" => "rtrim", // deprecated since TYPO3 4.5, will be removed in TYPO3 4.7 - Use rtrim() directly
				"t3lib_div::danish_strtoupper" => "t3lib_cs::conv_case", // deprecated since TYPO3 3.5, will be removed in TYPO3 4.6 - Use t3lib_cs::conv_case() instead or for HTML output, wrap your content in ...)"
				"t3lib_div::convUmlauts" => "t3lib_cs::specCharsToASCII", // deprecated since TYPO3 4.1, will be removed in TYPO3 4.6 - Works only for western europe single-byte charsets! Use t3lib_cs::specCharsToASCII() instead!
				"t3lib_div::uniqueArray" => "array_unique", // deprecated since TYPO3 3.5, will be removed in TYPO3 4.6 - Use the PHP function array_unique instead
				"t3lib_div::array2json" => "json_encode", // deprecated since TYPO3 4.3, will be removed in TYPO3 4.6 - use PHP native function json_encode() instead, will be removed in TYPO3 4.5
				"t3lib_div::implodeParams" => "t3lib_div::implodeAttributes", // deprecated since TYPO3 3.7, will be removed in TYPO3 4.6 - Name was changed into implodeAttributes
				"t3lib_div::debug_ordvalue" => "t3lib_utility_Debug::ordinalValue", // deprecated since TYPO3 4.5 - Use t3lib_utility_Debug::ordinalValue instead
				"t3lib_div::view_array" => "t3lib_utility_Debug::viewArray", // deprecated since TYPO3 4.5 - Use t3lib_utility_Debug::viewArray instead
				"t3lib_div::print_array" => "t3lib_utility_Debug::printArray", // deprecated since TYPO3 4.5 - Use t3lib_utility_Debug::printArray instead
				"t3lib_div::debug" => "t3lib_utility_Debug::debug", // deprecated since TYPO3 4.5 - Use t3lib_utility_Debug::debug instead
				"t3lib_div::debug_trail" => "t3lib_utility_Debug::debugTrail", // deprecated since TYPO3 4.5 - Use t3lib_utility_Debug::debugTrail instead
				"t3lib_div::debugRows" => "t3lib_utility_Debug::debugRows", // deprecated since TYPO3 4.5 - Use t3lib_utility_Debug::debugRows instead
				"t3lib_div::makeInstanceClassName" => "t3lib_div::makeInstance", // deprecated since TYPO3 4.3, will be removed in TYPO3 4.6 - Use t3lib_div::makeInstance('myClass', $arg1, $arg2, ..., $argN)
				#"t3lib_BEfunc::compilePreviewKeyword" => "", // deprecated since TYPO3 4.6, will be removed in TYPO3 4.8, functionality is now in Tx_Version_Preview
				"t3lib_div::breakLinesForEmail" => "t3lib_utility_Mail::breakLinesForEmail", // deprecated since TYPO3 4.6, will be removed in TYPO3 4.8 - Use t3lib_utility_Mail::breakLinesForEmail()
				"t3lib_div::intInRange" => "t3lib_utility_Math::forceIntegerInRange", // deprecated since TYPO3 4.6, will be removed in TYPO3 4.8 - Use t3lib_utility_Math::forceIntegerInRange() instead
				"t3lib_div::intval_positive" => "t3lib_utility_Math::convertToPositiveInteger", // deprecated since TYPO3 4.6, will be removed in TYPO3 4.8 - Use t3lib_utility_Math::convertToPositiveInteger() instead
				"t3lib_div::int_from_ver" => "t3lib_utility_VersionNumber::convertVersionNumberToInteger", // deprecated since TYPO3 4.6, will be removed in TYPO3 4.9 - Use t3lib_utility_VersionNumber::convertVersionNumberToInteger() instead
				"t3lib_div::testInt" => "t3lib_utility_Math::canBeInterpretedAsInteger", // deprecated since TYPO3 4.6, will be removed in TYPO3 4.8 - Use t3lib_utility_Math::canBeInterpretedAsInteger() instead
				"t3lib_div::calcPriority" => "t3lib_utility_Math::calculateWithPriorityToAdditionAndSubtraction", // deprecated since TYPO3 4.6, will be removed in TYPO3 4.8 - Use t3lib_utility_Math::calculateWithPriorityToAdditionAndSubtraction() instead
				"t3lib_div::calcParenthesis" => "t3lib_utility_Math::calculateWithParentheses", // deprecated since TYPO3 4.6, will be removed in TYPO3 4.8 - Use t3lib_utility_Math::calculateWithParentheses() instead
				#"t3lib_div::cHashParams" => "", // deprecated since TYPO3 4.7 - will be removed in TYPO3 4.9 - use t3lib_cacheHash instead
				#"t3lib_div::generateCHash" => "", // deprecated since TYPO3 4.7 - will be removed in TYPO3 4.9 - use t3lib_cacheHash instead
				#"t3lib_div::calculateCHash" => "", // deprecated since TYPO3 4.7 - will be removed in TYPO3 4.9 - use t3lib_cacheHash instead
				"t3lib_div::readLLPHPfile" => "t3lib_l10n_parser_Llphp::getParsedData", // deprecated since TYPO3 4.6, will be removed in TYPO3 4.8 - use t3lib_l10n_parser_Llphp::getParsedData() from now on
				"t3lib_div::readLLXMLfile" => "t3lib_l10n_parser_Llxml::getParsedData", // deprecated since TYPO3 4.6, will be removed in TYPO3 4.8 - use t3lib_l10n_parser_Llxml::getParsedData() from now on
				#"t3lib_cache::initPageCache" => "", // deprecated since TYPO3 4.6, will be removed in 4.8 - cacheManager->getCache() now initializes caches automatically
				#"t3lib_cache::initPageSectionCache" => "", // deprecated since TYPO3 4.6, will be removed in 4.8 - cacheManager->getCache() now initializes caches automatically
				#"t3lib_cache::initContentHashCache" => "", // deprecated since TYPO3 4.6, will be removed in 4.8 - cacheManager->getCache() now initializes caches automatically
				#"t3lib_cache::enableCachingFramework" => "", // deprecated since 4.6, will be removed in 4.8: The caching framework is enabled by default


			// Non Static Calls
				// TODO



			// Namespaces
					
				// deprecated since 6.0 will be removed in 7.0
				"t3lib_TCEforms_inline"  => "\TYPO3\CMS\Backend\Form\Element\InlineElement",
				"t3lib_tceformsInlineHook"  => "\TYPO3\CMS\Backend\Form\Element\InlineElementHookInterface",
				"t3lib_TCEforms_FE"  => "\TYPO3\CMS\Backend\Form\FrontendFormEngine",
				"t3lib_TCEforms_dbFileIconsHook"  => "\TYPO3\CMS\Backend\Form\DatabaseFileIconsHookInterface",
				"t3lib_TCEforms_Suggest_DefaultReceiver"  => "\TYPO3\CMS\Backend\Form\Element\SuggestDefaultReceiver",
				"t3lib_TCEforms_Suggest"  => "\TYPO3\CMS\Backend\Form\Element\SuggestElement",
				"t3lib_TCEforms_Tree"  => "\TYPO3\CMS\Backend\Form\Element\TreeElement",
				"t3lib_TCEforms_ValueSlider"  => "\TYPO3\CMS\Backend\Form\Element\ValueSlider",
				"t3lib_TCEforms_Flexforms"  => "\TYPO3\CMS\Backend\Form\FlexFormsHelper",
				"t3lib_TCEforms"  => "\TYPO3\CMS\Backend\Form\FormEngine",
				"t3lib_tsfeBeUserAuth"  => "\TYPO3\CMS\Backend\FrontendBackendUserAuthentication",
				"t3lib_extobjbase"  => "\TYPO3\CMS\Backend\Module\AbstractFunctionModule",
				"t3lib_SCbase"  => "\TYPO3\CMS\Backend\Module\BaseScriptClass",
				"t3lib_loadModules"  => "\TYPO3\CMS\Backend\Module\ModuleLoader",
				"t3lib_modSettings"  => "\TYPO3\CMS\Backend\ModuleSettings",
				"t3lib_recordList"  => "\TYPO3\CMS\Backend\RecordList\AbstractRecordList",
				"t3lib_localRecordListGetTableHook"  => "\TYPO3\CMS\Backend\RecordList\RecordListGetTableHookInterface",
				"t3lib_rteapi"  => "\TYPO3\CMS\Backend\Rte\AbstractRte",
				"t3lib_search_liveSearch"  => "\TYPO3\CMS\Backend\Search\LiveSearch\LiveSearch",
				"t3lib_search_liveSearch_queryParser"  => "\TYPO3\CMS\Backend\Search\LiveSearch\QueryParser",
				"t3lib_spritemanager_AbstractHandler"  => "\TYPO3\CMS\Backend\Sprite\AbstractSpriteHandler",
				"t3lib_spritemanager_SimpleHandler"  => "\TYPO3\CMS\Backend\Sprite\SimpleSpriteHandler",
				"t3lib_spritemanager_SpriteBuildingHandler"  => "\TYPO3\CMS\Backend\Sprite\SpriteBuildingHandler",
				"t3lib_spritemanager_SpriteGenerator"  => "\TYPO3\CMS\Backend\Sprite\SpriteGenerator",
				"t3lib_spritemanager_SpriteIconGenerator"  => "\TYPO3\CMS\Backend\Sprite\SpriteIconGeneratorInterface",
				"t3lib_SpriteManager"  => "\TYPO3\CMS\Backend\Sprite\SpriteManager",
				"t3lib_tree_ExtDirect_AbstractExtJsTree"  => "\TYPO3\CMS\Backend\Tree\AbstractExtJsTree",
				"t3lib_tree_AbstractTree"  => "\TYPO3\CMS\Backend\Tree\AbstractTree",
				"t3lib_tree_AbstractDataProvider"  => "\TYPO3\CMS\Backend\Tree\AbstractTreeDataProvider",
				"t3lib_tree_AbstractStateProvider"  => "\TYPO3\CMS\Backend\Tree\AbstractTreeStateProvider",
				"t3lib_tree_ComparableNode"  => "\TYPO3\CMS\Backend\Tree\ComparableNodeInterface",
				"t3lib_tree_DraggableAndDropable"  => "\TYPO3\CMS\Backend\Tree\DraggableAndDropableNodeInterface",
				"t3lib_tree_LabelEditable"  => "\TYPO3\CMS\Backend\Tree\EditableNodeLabelInterface",
				"t3lib_tree_extdirect_Node"  => "\TYPO3\CMS\Backend\Tree\ExtDirectNode",
				"t3lib_tree_pagetree_interfaces_CollectionProcessor"  => "\TYPO3\CMS\Backend\Tree\Pagetree\CollectionProcessorInterface",
				"t3lib_tree_pagetree_Commands"  => "\TYPO3\CMS\Backend\Tree\Pagetree\Commands",
				"t3lib_tree_pagetree_DataProvider"  => "\TYPO3\CMS\Backend\Tree\Pagetree\DataProvider",
				"t3lib_tree_pagetree_extdirect_Commands"  => "\TYPO3\CMS\Backend\Tree\Pagetree\ExtdirectTreeCommands",
				"t3lib_tree_pagetree_extdirect_Tree"  => "\TYPO3\CMS\Backend\Tree\Pagetree\ExtdirectTreeDataProvider",
				"t3lib_tree_pagetree_Indicator"  => "\TYPO3\CMS\Backend\Tree\Pagetree\Indicator",
				"t3lib_tree_pagetree_interfaces_IndicatorProvider"  => "\TYPO3\CMS\Backend\Tree\Pagetree\IndicatorProviderInterface",
				"t3lib_tree_pagetree_Node"  => "\TYPO3\CMS\Backend\Tree\Pagetree\PagetreeNode",
				"t3lib_tree_pagetree_NodeCollection"  => "\TYPO3\CMS\Backend\Tree\Pagetree\PagetreeNodeCollection",
				"t3lib_tree_Renderer_Abstract"  => "\TYPO3\CMS\Backend\Tree\Renderer\AbstractTreeRenderer",
				"t3lib_tree_Renderer_ExtJsJson"  => "\TYPO3\CMS\Backend\Tree\Renderer\ExtJsJsonTreeRenderer",
				"t3lib_tree_Renderer_UnorderedList"  => "\TYPO3\CMS\Backend\Tree\Renderer\UnorderedListTreeRenderer",
				"t3lib_tree_SortedNodeCollection"  => "\TYPO3\CMS\Backend\Tree\SortedTreeNodeCollection",
				"t3lib_tree_Node"  => "\TYPO3\CMS\Backend\Tree\TreeNode",
				"t3lib_tree_NodeCollection"  => "\TYPO3\CMS\Backend\Tree\TreeNodeCollection",
				"t3lib_tree_RepresentationNode"  => "\TYPO3\CMS\Backend\Tree\TreeRepresentationNode",
				"t3lib_treeView"  => "\TYPO3\CMS\Backend\Tree\View\AbstractTreeView",
				"t3lib_browseTree"  => "\TYPO3\CMS\Backend\Tree\View\BrowseTreeView",
				"t3lib_folderTree"  => "\TYPO3\CMS\Backend\Tree\View\FolderTreeView",
				"t3lib_positionMap"  => "\TYPO3\CMS\Backend\Tree\View\PagePositionMap",
				"t3lib_pageTree"  => "\TYPO3\CMS\Backend\Tree\View\PageTreeView",
				"t3lib_BEfunc"  => "\TYPO3\CMS\Backend\Utility\BackendUtility",
				"t3lib_iconWorks"  => "\TYPO3\CMS\Backend\Utility\IconUtility",
				"t3lib_extjs_ExtDirectRouter"  => "\TYPO3\CMS\Core\ExtDirect\ExtDirectRouter",
				"t3lib_extjs_ExtDirectApi"  => "\TYPO3\CMS\Core\ExtDirect\ExtDirectApi",
				"t3lib_extjs_ExtDirectDebug"  => "\TYPO3\CMS\Core\ExtDirect\ExtDirectDebug",
				"t3lib_cli"  => "\TYPO3\CMS\Core\Controller\CommandLineController",
				"t3lib_userAuth"  => "\TYPO3\CMS\Core\Authentication\AbstractUserAuthentication",
				"t3lib_beUserAuth"  => "\TYPO3\CMS\Core\Authentication\BackendUserAuthentication",
				"t3lib_autoloader"  => "\TYPO3\CMS\Core\Core\ClassLoader",
				"t3lib_cache_backend_AbstractBackend"  => "\TYPO3\CMS\Core\Cache\Backend\AbstractBackend",
				"t3lib_cache_backend_ApcBackend"  => "\TYPO3\CMS\Core\Cache\Backend\ApcBackend",
				"t3lib_cache_backend_Backend"  => "\TYPO3\CMS\Core\Cache\Backend\BackendInterface",
				"t3lib_cache_backend_FileBackend"  => "\TYPO3\CMS\Core\Cache\Backend\FileBackend",
				"t3lib_cache_backend_MemcachedBackend"  => "\TYPO3\CMS\Core\Cache\Backend\MemcachedBackend",
				"t3lib_cache_backend_NullBackend"  => "\TYPO3\CMS\Core\Cache\Backend\NullBackend",
				"t3lib_cache_backend_PdoBackend"  => "\TYPO3\CMS\Core\Cache\Backend\PdoBackend",
				"t3lib_cache_backend_PhpCapableBackend"  => "\TYPO3\CMS\Core\Cache\Backend\PhpCapableBackendInterface",
				"t3lib_cache_backend_RedisBackend"  => "\TYPO3\CMS\Core\Cache\Backend\RedisBackend",
				"t3lib_cache_backend_TransientMemoryBackend"  => "\TYPO3\CMS\Core\Cache\Backend\TransientMemoryBackend",
				"t3lib_cache_backend_DbBackend"  => "\TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend",
				"t3lib_cache"  => "\TYPO3\CMS\Core\Cache\Cache",
				"t3lib_cache_Factory"  => "\TYPO3\CMS\Core\Cache\CacheFactory",
				"t3lib_cache_Manager"  => "\TYPO3\CMS\Core\Cache\CacheManager",
				"t3lib_cache_Exception"  => "\TYPO3\CMS\Core\Cache\Exception",
				"t3lib_cache_exception_ClassAlreadyLoaded"  => "\TYPO3\CMS\Core\Cache\Exception\ClassAlreadyLoadedException",
				"t3lib_cache_exception_DuplicateIdentifier"  => "\TYPO3\CMS\Core\Cache\Exception\DuplicateIdentifierException",
				"t3lib_cache_exception_InvalidBackend"  => "\TYPO3\CMS\Core\Cache\Exception\InvalidBackendException",
				"t3lib_cache_exception_InvalidCache"  => "\TYPO3\CMS\Core\Cache\Exception\InvalidCacheException",
				"t3lib_cache_exception_InvalidData"  => "\TYPO3\CMS\Core\Cache\Exception\InvalidDataException",
				"t3lib_cache_exception_NoSuchCache"  => "\TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException",
				"t3lib_cache_frontend_AbstractFrontend"  => "\TYPO3\CMS\Core\Cache\Frontend\AbstractFrontend",
				"t3lib_cache_frontend_Frontend"  => "\TYPO3\CMS\Core\Cache\Frontend\FrontendInterface",
				"t3lib_cache_frontend_PhpFrontend"  => "\TYPO3\CMS\Core\Cache\Frontend\PhpFrontend",
				"t3lib_cache_frontend_StringFrontend"  => "\TYPO3\CMS\Core\Cache\Frontend\StringFrontend",
				"t3lib_cache_frontend_VariableFrontend"  => "\TYPO3\CMS\Core\Cache\Frontend\VariableFrontend",
				"t3lib_cs"  => "\TYPO3\CMS\Core\Charset\CharsetConverter",
				"t3lib_collection_AbstractRecordCollection"  => "\TYPO3\CMS\Core\Collection\AbstractRecordCollection",
				"t3lib_collection_Collection"  => "\TYPO3\CMS\Core\Collection\CollectionInterface",
				"t3lib_collection_Editable"  => "\TYPO3\CMS\Core\Collection\EditableCollectionInterface",
				"t3lib_collection_Nameable"  => "\TYPO3\CMS\Core\Collection\NameableCollectionInterface",
				"t3lib_collection_Persistable"  => "\TYPO3\CMS\Core\Collection\PersistableCollectionInterface",
				"t3lib_collection_RecordCollection"  => "\TYPO3\CMS\Core\Collection\RecordCollectionInterface",
				"t3lib_collection_RecordCollectionRepository"  => "\TYPO3\CMS\Core\Collection\RecordCollectionRepository",
				"t3lib_collection_Sortable"  => "\TYPO3\CMS\Core\Collection\SortableCollectionInterface",
				"t3lib_collection_StaticRecordCollection"  => "\TYPO3\CMS\Core\Collection\StaticRecordCollection",
				"t3lib_flexformtools"  => "\TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools",
				"t3lib_matchCondition_abstract"  => "\TYPO3\CMS\Core\Configuration\TypoScript\ConditionMatching\AbstractConditionMatcher",
				"t3lib_DB"  => "\TYPO3\CMS\Core\Database\DatabaseConnection",
				"t3lib_PdoHelper"  => "\TYPO3\CMS\Core\Database\PdoHelper",
				"t3lib_DB_postProcessQueryHook"  => "\TYPO3\CMS\Core\Database\PostProcessQueryHookInterface",
				"t3lib_db_PreparedStatement"  => "\TYPO3\CMS\Core\Database\PreparedStatement",
				"t3lib_DB_preProcessQueryHook"  => "\TYPO3\CMS\Core\Database\PreProcessQueryHookInterface",
				"t3lib_queryGenerator"  => "\TYPO3\CMS\Core\Database\QueryGenerator",
				"t3lib_fullsearch"  => "\TYPO3\CMS\Core\Database\QueryView",
				"t3lib_refindex"  => "\TYPO3\CMS\Core\Database\ReferenceIndex",
				"t3lib_loadDBGroup"  => "\TYPO3\CMS\Core\Database\RelationHandler",
				"t3lib_softrefproc"  => "\TYPO3\CMS\Core\Database\SoftReferenceIndex",
				"t3lib_sqlparser"  => "\TYPO3\CMS\Core\Database\SqlParser",
				"t3lib_extTables_PostProcessingHook"  => "\TYPO3\CMS\Core\Database\TableConfigurationPostProcessingHookInterface",
				"t3lib_TCEmain"  => "\TYPO3\CMS\Core\DataHandling\DataHandler",
				"t3lib_TCEmain_checkModifyAccessListHook"  => "\TYPO3\CMS\Core\DataHandling\DataHandlerCheckModifyAccessListHookInterface",
				"t3lib_TCEmain_processUploadHook"  => "\TYPO3\CMS\Core\DataHandling\DataHandlerProcessUploadHookInterface",
				"t3lib_browseLinksHook"  => "\TYPO3\CMS\Core\ElementBrowser\ElementBrowserHookInterface",
				"t3lib_codec_JavaScriptEncoder"  => "\TYPO3\CMS\Core\Encoder\JavaScriptEncoder",
				"t3lib_error_AbstractExceptionHandler"  => "\TYPO3\CMS\Core\Error\AbstractExceptionHandler",
				"t3lib_error_DebugExceptionHandler"  => "\TYPO3\CMS\Core\Error\DebugExceptionHandler",
				"t3lib_error_ErrorHandler"  => "\TYPO3\CMS\Core\Error\ErrorHandler",
				"t3lib_error_ErrorHandlerInterface"  => "\TYPO3\CMS\Core\Error\ErrorHandlerInterface",
				"t3lib_error_Exception"  => "\TYPO3\CMS\Core\Error\Exception",
				"t3lib_error_ExceptionHandlerInterface"  => "\TYPO3\CMS\Core\Error\ExceptionHandlerInterface",
				"t3lib_error_http_AbstractClientErrorException"  => "\TYPO3\CMS\Core\Error\Http\AbstractClientErrorException",
				"t3lib_error_http_AbstractServerErrorException"  => "\TYPO3\CMS\Core\Error\Http\AbstractServerErrorException",
				"t3lib_error_http_BadRequestException"  => "\TYPO3\CMS\Core\Error\Http\BadRequestException",
				"t3lib_error_http_ForbiddenException"  => "\TYPO3\CMS\Core\Error\Http\ForbiddenException",
				"t3lib_error_http_PageNotFoundException"  => "\TYPO3\CMS\Core\Error\Http\PageNotFoundException",
				"t3lib_error_http_ServiceUnavailableException"  => "\TYPO3\CMS\Core\Error\Http\ServiceUnavailableException",
				"t3lib_error_http_StatusException"  => "\TYPO3\CMS\Core\Error\Http\StatusException",
				"t3lib_error_http_UnauthorizedException"  => "\TYPO3\CMS\Core\Error\Http\UnauthorizedException",
				"t3lib_error_ProductionExceptionHandler"  => "\TYPO3\CMS\Core\Error\ProductionExceptionHandler",
				"t3lib_exception"  => "\TYPO3\CMS\Core\Exception",
				"t3lib_extMgm"  => "\TYPO3\CMS\Core\Utility\ExtensionManagementUtility",
				"t3lib_formprotection_Abstract"  => "\TYPO3\CMS\Core\FormProtection\AbstractFormProtection",
				"t3lib_formprotection_BackendFormProtection"  => "\TYPO3\CMS\Core\FormProtection\BackendFormProtection",
				"t3lib_formprotection_DisabledFormProtection"  => "\TYPO3\CMS\Core\FormProtection\DisabledFormProtection",
				"t3lib_formprotection_InvalidTokenException"  => "\TYPO3\CMS\Core\FormProtection\Exception",
				"t3lib_formprotection_Factory"  => "\TYPO3\CMS\Core\FormProtection\FormProtectionFactory",
				"t3lib_formprotection_InstallToolFormProtection"  => "\TYPO3\CMS\Core\FormProtection\InstallToolFormProtection",
				"t3lib_frontendedit"  => "\TYPO3\CMS\Core\FrontendEditing\FrontendEditingController",
				"t3lib_parsehtml"  => "\TYPO3\CMS\Core\Html\HtmlParser",
				"t3lib_parsehtml_proc"  => "\TYPO3\CMS\Core\Html\RteHtmlParser",
				"t3lib_http_Request"  => "\TYPO3\CMS\Core\Http\HttpRequest",
				"t3lib_http_observer_Download"  => "\TYPO3\CMS\Core\Http\Observer\Download",
				"t3lib_stdGraphic"  => "\TYPO3\CMS\Core\Imaging\GraphicalFunctions",
				"t3lib_admin"  => "\TYPO3\CMS\Core\Integrity\DatabaseIntegrityCheck",
				"t3lib_l10n_exception_FileNotFound"  => "\TYPO3\CMS\Core\Localization\Exception\FileNotFoundException",
				"t3lib_l10n_exception_InvalidParser"  => "\TYPO3\CMS\Core\Localization\Exception\InvalidParserException",
				"t3lib_l10n_exception_InvalidXmlFile"  => "\TYPO3\CMS\Core\Localization\Exception\InvalidXmlFileException",
				"t3lib_l10n_Store"  => "\TYPO3\CMS\Core\Localization\LanguageStore",
				"t3lib_l10n_Locales"  => "\TYPO3\CMS\Core\Localization\Locales",
				"t3lib_l10n_Factory"  => "\TYPO3\CMS\Core\Localization\LocalizationFactory",
				"t3lib_l10n_parser_AbstractXml"  => "\TYPO3\CMS\Core\Localization\Parser\AbstractXmlParser",
				"t3lib_l10n_parser"  => "\TYPO3\CMS\Core\Localization\Parser\LocalizationParserInterface",
				"t3lib_l10n_parser_Llphp"  => "\TYPO3\CMS\Core\Localization\Parser\LocallangArrayParser",
				"t3lib_l10n_parser_Llxml"  => "\TYPO3\CMS\Core\Localization\Parser\LocallangXmlParser",
				"t3lib_l10n_parser_Xliff"  => "\TYPO3\CMS\Core\Localization\Parser\XliffParser",
				"t3lib_lock"  => "\TYPO3\CMS\Core\Locking\Locker",
				"t3lib_mail_Mailer"  => "\TYPO3\CMS\Core\Mail\Mailer",
				"t3lib_mail_MailerAdapter"  => "\TYPO3\CMS\Core\Mail\MailerAdapterInterface",
				"t3lib_mail_Message"  => "\TYPO3\CMS\Core\Mail\MailMessage",
				"t3lib_mail_MboxTransport"  => "\TYPO3\CMS\Core\Mail\MboxTransport",
				"t3lib_mail_Rfc822AddressesParser"  => "\TYPO3\CMS\Core\Mail\Rfc822AddressesParser",
				"t3lib_mail_SwiftMailerAdapter"  => "\TYPO3\CMS\Core\Mail\SwiftMailerAdapter",
				"t3lib_message_AbstractMessage"  => "\TYPO3\CMS\Core\Messaging\AbstractMessage",
				"t3lib_message_AbstractStandaloneMessage"  => "\TYPO3\CMS\Core\Messaging\AbstractStandaloneMessage",
				"t3lib_message_ErrorpageMessage"  => "\TYPO3\CMS\Core\Messaging\ErrorpageMessage",
				"t3lib_FlashMessage"  => "\TYPO3\CMS\Core\Messaging\FlashMessage",
				"t3lib_FlashMessageQueue"  => "\TYPO3\CMS\Core\Messaging\FlashMessageQueue",
				"t3lib_PageRenderer"  => "\TYPO3\CMS\Core\Page\PageRenderer",
				"t3lib_Registry"  => "\TYPO3\CMS\Core\Registry",
				"t3lib_Compressor"  => "\TYPO3\CMS\Core\Resource\ResourceCompressor",
				"t3lib_svbase"  => "\TYPO3\CMS\Core\Service\AbstractService",
				"t3lib_Singleton"  => "\TYPO3\CMS\Core\SingletonInterface",
				"t3lib_TimeTrackNull"  => "\TYPO3\CMS\Core\TimeTracker\NullTimeTracker",
				"t3lib_timeTrack"  => "\TYPO3\CMS\Core\TimeTracker\TimeTracker",
				"t3lib_tree_Tca_AbstractTcaTreeDataProvider"  => "\TYPO3\CMS\Core\Tree\TableConfiguration\AbstractTableConfigurationTreeDataProvider",
				"t3lib_tree_Tca_DatabaseTreeDataProvider"  => "\TYPO3\CMS\Core\Tree\TableConfiguration\DatabaseTreeDataProvider",
				"t3lib_tree_Tca_DatabaseNode"  => "\TYPO3\CMS\Core\Tree\TableConfiguration\DatabaseTreeNode",
				"t3lib_tree_Tca_ExtJsArrayRenderer"  => "\TYPO3\CMS\Core\Tree\TableConfiguration\ExtJsArrayTreeRenderer",
				"t3lib_tree_Tca_TcaTree"  => "\TYPO3\CMS\Core\Tree\TableConfiguration\TableConfigurationTree",
				"t3lib_tree_Tca_DataProviderFactory"  => "\TYPO3\CMS\Core\Tree\TableConfiguration\TreeDataProviderFactory",
				"t3lib_tsStyleConfig"  => "\TYPO3\CMS\Core\TypoScript\ConfigurationForm",
				"t3lib_tsparser_ext"  => "\TYPO3\CMS\Core\TypoScript\ExtendedTemplateService",
				"t3lib_TSparser"  => "\TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser",
				"t3lib_TStemplate"  => "\TYPO3\CMS\Core\TypoScript\TemplateService",
				"t3lib_utility_Array"  => "\TYPO3\CMS\Core\Utility\ArrayUtility",
				"t3lib_utility_Client"  => "\TYPO3\CMS\Core\Utility\ClientUtility",
				"t3lib_exec"  => "\TYPO3\CMS\Core\Utility\CommandUtility",
				"t3lib_utility_Command"  => "\TYPO3\CMS\Core\Utility\CommandUtility",
				"t3lib_utility_Debug"  => "\TYPO3\CMS\Core\Utility\DebugUtility",
				"t3lib_diff"  => "\TYPO3\CMS\Core\Utility\DiffUtility",
				"t3lib_basicFileFunctions"  => "\TYPO3\CMS\Core\Utility\File\BasicFileUtility",
				"t3lib_extFileFunctions"  => "\TYPO3\CMS\Core\Utility\File\ExtendedFileUtility",
				"t3lib_extFileFunctions_processDataHook"  => "\TYPO3\CMS\Core\Utility\File\ExtendedFileUtilityProcessDataHookInterface",
				"t3lib_div"  => "\TYPO3\CMS\Core\Utility\GeneralUtility",
				"t3lib_utility_Http"  => "\TYPO3\CMS\Core\Utility\HttpUtility",
				"t3lib_utility_Mail"  => "\TYPO3\CMS\Core\Utility\MailUtility",
				"t3lib_utility_Math"  => "\TYPO3\CMS\Core\Utility\MathUtility",
				"t3lib_utility_Monitor"  => "\TYPO3\CMS\Core\Utility\MonitorUtility",
				"t3lib_utility_Path"  => "\TYPO3\CMS\Core\Utility\PathUtility",
				"t3lib_utility_PhpOptions"  => "\TYPO3\CMS\Core\Utility\PhpOptionsUtility",
				"t3lib_utility_VersionNumber"  => "\TYPO3\CMS\Core\Utility\VersionNumberUtility",
				"t3lib_matchCondition_frontend"  => "\TYPO3\CMS\Frontend\Configuration\TypoScript\ConditionMatching\ConditionMatcher",
				"tslib_content_Abstract"  => "\TYPO3\CMS\Frontend\ContentObject\AbstractContentObject",
				"tslib_content_Case"  => "\TYPO3\CMS\Frontend\ContentObject\CaseContentObject",
				"tslib_content_ClearGif"  => "\TYPO3\CMS\Frontend\ContentObject\ClearGifContentObject",
				"tslib_content_Columns"  => "\TYPO3\CMS\Frontend\ContentObject\ColumnsContentObject",
				"tslib_content_Content"  => "\TYPO3\CMS\Frontend\ContentObject\ContentContentObject",
				"tslib_content_ContentObjectArray"  => "\TYPO3\CMS\Frontend\ContentObject\ContentObjectArrayContentObject",
				"tslib_content_ContentObjectArrayInternal"  => "\TYPO3\CMS\Frontend\ContentObject\ContentObjectArrayInternalContentObject",
				"tslib_content_getDataHook"  => "\TYPO3\CMS\Frontend\ContentObject\ContentObjectGetDataHookInterface",
				"tslib_cObj_getImgResourceHook"  => "\TYPO3\CMS\Frontend\ContentObject\ContentObjectGetImageResourceHookInterface",
				"tslib_content_getPublicUrlForFileHook"  => "\TYPO3\CMS\Frontend\ContentObject\ContentObjectGetPublicUrlForFileHookInterface",
				"tslib_content_cObjGetSingleHook"  => "\TYPO3\CMS\Frontend\ContentObject\ContentObjectGetSingleHookInterface",
				"tslib_content_PostInitHook"  => "\TYPO3\CMS\Frontend\ContentObject\ContentObjectPostInitHookInterface",
				"tslib_cObj"  => "\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer",
				"tslib_content_stdWrapHook"  => "\TYPO3\CMS\Frontend\ContentObject\ContentObjectStdWrapHookInterface",
				"tslib_content_ContentTable"  => "\TYPO3\CMS\Frontend\ContentObject\ContentTableContentObject",
				"tslib_content_EditPanel"  => "\TYPO3\CMS\Frontend\ContentObject\EditPanelContentObject",
				"tslib_content_File"  => "\TYPO3\CMS\Frontend\ContentObject\FileContentObject",
				"tslib_content_fileLinkHook"  => "\TYPO3\CMS\Frontend\ContentObject\FileLinkHookInterface",
				"tslib_content_Files"  => "\TYPO3\CMS\Frontend\ContentObject\FilesContentObject",
				"tslib_content_FlowPlayer"  => "\TYPO3\CMS\Frontend\ContentObject\FlowPlayerContentObject",
				"tslib_content_FluidTemplate"  => "\TYPO3\CMS\Frontend\ContentObject\FluidTemplateContentObject",
				"tslib_content_Form"  => "\TYPO3\CMS\Frontend\ContentObject\FormContentObject",
				"tslib_content_HierarchicalMenu"  => "\TYPO3\CMS\Frontend\ContentObject\HierarchicalMenuContentObject",
				"tslib_content_HorizontalRuler"  => "\TYPO3\CMS\Frontend\ContentObject\HorizontalRulerContentObject",
				"tslib_content_Image"  => "\TYPO3\CMS\Frontend\ContentObject\ImageContentObject",
				"tslib_content_ImageResource"  => "\TYPO3\CMS\Frontend\ContentObject\ImageResourceContentObject",
				"tslib_content_ImageText"  => "\TYPO3\CMS\Frontend\ContentObject\ImageTextContentObject",
				"tslib_content_LoadRegister"  => "\TYPO3\CMS\Frontend\ContentObject\LoadRegisterContentObject",
				"tslib_content_Media"  => "\TYPO3\CMS\Frontend\ContentObject\MediaContentObject",
				"tslib_menu"  => "\TYPO3\CMS\Frontend\ContentObject\Menu\AbstractMenuContentObject",
				"tslib_menu_filterMenuPagesHook"  => "\TYPO3\CMS\Frontend\ContentObject\Menu\AbstractMenuFilterPagesHookInterface",
				"tslib_gmenu"  => "\TYPO3\CMS\Frontend\ContentObject\Menu\GraphicalMenuContentObject",
				"tslib_gmenu_foldout"  => "\TYPO3\CMS\Frontend\ContentObject\Menu\GraphicalMenuFoldoutContentObject",
				"tslib_gmenu_layers"  => "\TYPO3\CMS\Frontend\ContentObject\Menu\GraphicalMenuLayersContentObject",
				"tslib_imgmenu"  => "\TYPO3\CMS\Frontend\ContentObject\Menu\ImageMenuContentObject",
				"tslib_jsmenu"  => "\TYPO3\CMS\Frontend\ContentObject\Menu\JavaScriptMenuContentObject",
				"tslib_tmenu"  => "\TYPO3\CMS\Frontend\ContentObject\Menu\TextMenuContentObject",
				"tslib_tmenu_layers"  => "\TYPO3\CMS\Frontend\ContentObject\Menu\TextMenuLayersContentObject",
				"tslib_content_Multimedia"  => "\TYPO3\CMS\Frontend\ContentObject\MultimediaContentObject",
				"tslib_tableOffset"  => "\TYPO3\CMS\Frontend\ContentObject\OffsetTableContentObject",
				"tslib_content_OffsetTable"  => "\TYPO3\CMS\Frontend\ContentObject\OffsetTableContentObject",
				"tslib_content_QuicktimeObject"  => "\TYPO3\CMS\Frontend\ContentObject\QuicktimeObjectContentObject",
				"tslib_content_Records"  => "\TYPO3\CMS\Frontend\ContentObject\RecordsContentObject",
				"tslib_content_RestoreRegister"  => "\TYPO3\CMS\Frontend\ContentObject\RestoreRegisterContentObject",
				"tslib_content_ScalableVectorGraphics"  => "\TYPO3\CMS\Frontend\ContentObject\ScalableVectorGraphicsContentObject",
				"tslib_search"  => "\TYPO3\CMS\Frontend\ContentObject\SearchResultContentObject",
				"tslib_content_SearchResult"  => "\TYPO3\CMS\Frontend\ContentObject\SearchResultContentObject",
				"tslib_content_ShockwaveFlashObject"  => "\TYPO3\CMS\Frontend\ContentObject\ShockwaveFlashObjectContentObject",
				"tslib_controlTable"  => "\TYPO3\CMS\Frontend\ContentObject\TableRenderer",
				"tslib_content_Template"  => "\TYPO3\CMS\Frontend\ContentObject\TemplateContentObject",
				"tslib_content_Text"  => "\TYPO3\CMS\Frontend\ContentObject\TextContentObject",
				"tslib_content_User"  => "\TYPO3\CMS\Frontend\ContentObject\UserContentObject",
				"tslib_content_UserInternal"  => "\TYPO3\CMS\Frontend\ContentObject\UserInternalContentObject",
				"tslib_ExtDirectEid"  => "\TYPO3\CMS\Frontend\Controller\ExtDirectEidController",
				"tslib_fe"  => "\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController",
				"tslib_gifBuilder"  => "\TYPO3\CMS\Frontend\Imaging\GifBuilder",
				"tslib_mediaWizardCoreProvider"  => "\TYPO3\CMS\Frontend\MediaWizard\MediaWizardProvider",
				"tslib_mediaWizardProvider"  => "\TYPO3\CMS\Frontend\MediaWizard\MediaWizardProviderInterface",
				"tslib_mediaWizardManager"  => "\TYPO3\CMS\Frontend\MediaWizard\MediaWizardProviderManager",
				"t3lib_cacheHash"  => "\TYPO3\CMS\Frontend\Page\CacheHashCalculator",
				"tslib_frameset"  => "\TYPO3\CMS\Frontend\Page\FramesetRenderer",
				"t3lib_pageSelect"  => "\TYPO3\CMS\Frontend\Page\PageRepository",
				"t3lib_pageSelect_getPageHook"  => "\TYPO3\CMS\Frontend\Page\PageRepositoryGetPageHookInterface",
				"t3lib_pageSelect_getPageOverlayHook"  => "\TYPO3\CMS\Frontend\Page\PageRepositoryGetPageOverlayHookInterface",
				"t3lib_pageSelect_getRecordOverlayHook"  => "\TYPO3\CMS\Frontend\Page\PageRepositoryGetRecordOverlayHookInterface",
				"tslib_pibase"  => "\TYPO3\CMS\Frontend\Plugin\AbstractPlugin",
				"tslib_fecompression"  => "\TYPO3\CMS\Frontend\Utility\CompressionUtility",
				"tslib_eidtools"  => "\TYPO3\CMS\Frontend\Utility\EidUtility",
				"tslib_AdminPanel"  => "\TYPO3\CMS\Frontend\View\AdminPanelView",
				"tslib_adminPanelHook"  => "\TYPO3\CMS\Frontend\View\AdminPanelViewHookInterface",
			)
		);
	}

}

?>