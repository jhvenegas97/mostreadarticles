<?php

import('lib.pkp.classes.db.DAO');
import('lib.pkp.classes.cache.CacheManager');

class MostReadArticlesPluginDAO extends DAO {

    /**
     * Fetch the most-read articles for a given journal
     *
     * @param int $journalId
     * @param int $limit
     * @param string $locale
     * @return array
     */
    public function getMostReadArticles($journalId, $limit = 5, $locale = 'es_ES') {
        
        $articles = apcu_fetch("mostRead");
        
        if($articles){
            return $articles;
        }

        // Si no están en caché, realizar la consulta a la base de datos
        $query = '
            SELECT 
                m.submission_id AS article_id,
                ps.setting_value AS title,
                SUM(m.metric) AS total_views
            FROM 
                metrics m
            JOIN
                publications p ON p.submission_id = m.submission_id
            JOIN
                publication_settings ps ON p.publication_id = ps.publication_id
            WHERE 
                m.assoc_type = ?
                AND  m.metric_type = ?
                AND m.context_id = ?
                AND ps.setting_name = ?
                AND ps.setting_value IS NOT NULL AND ps.setting_value <> \'\' 
                AND ps.locale = ?
            GROUP BY 
                m.submission_id, ps.setting_value
            ORDER BY 
                total_views DESC
            LIMIT ?';

        $params = [
            515,              // View/downloads
            'ojs::counter',   // Metric type for views/downloads
            (int)$journalId,  // Journal ID
            'title',          // Setting name for the article title
            $locale,          // Locale (e.g., es_ES or en_US)
            (int)$limit       // Limit results
        ];

        $result = $this->retrieve($query, $params);

        $articles = [];
        foreach ($result as $row) {
            $articles[] = [
                'article_id' => $row->article_id,
                'title' => $row->title,
                'total_views' => $row->total_views
            ];
        }

        apcu_store("mostRead", $articles, 86400);
    }
}
