<?php

// Import the base DAO class
import('lib.pkp.classes.db.DAO');

class MostReadArticlesPluginDAO extends DAO {
    
    /**
     * Fetch the most-read articles for a given journal
     * 
     * @param int $journalId
     * @param int $limit
     * @return array
     */
    public function getMostReadArticles($journalId, $limit = 5, $locale = 'es_ES') {
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
            $locale,          // Locale to search es_ES en_US
            (int)$limit       // Number of results to return
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

        return $articles;
    }
}
