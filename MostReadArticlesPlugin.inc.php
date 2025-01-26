<?php

// Import the parent plugin class and DAO
import('lib.pkp.classes.plugins.BlockPlugin');
import('plugins.blocks.mostReadArticles.MostReadArticlesPluginDAO');

class MostReadArticlesPlugin extends BlockPlugin {
    
    /**
     * Get the display name of this plugin
     * 
     * @return string
     */
    public function getDisplayName() {
        return __('plugins.blocks.mostReadArticles.displayName');
    }

    /**
     * Get the description of this plugin
     * 
     * @return string
     */
    public function getDescription() {
        return __('plugins.blocks.mostReadArticles.description');
    }

    /**
     * Get the content for the block
     * 
     * @param $templateMgr PKPTemplateManager
     * @param $request PKPRequest
     * @return string
     */
    public function getContents($templateMgr, $request = null) {
        // Get the journal ID
        $journalId = $request->getContext()->getId();

        // Use the DAO to fetch most-read articles
        $dao = new MostReadArticlesPluginDAO();
        $mostReadArticles = $dao->getMostReadArticles($journalId, 5, 'es_ES');

        // Assign the data to the template
        $templateMgr->assign('mostReadArticles', $mostReadArticles);

        // Return the content rendered by the plugin template
        return parent::getContents($templateMgr, $request);
    }
}
