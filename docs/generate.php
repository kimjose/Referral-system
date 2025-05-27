#!/usr/bin/env php
<?php

class DocumentationGenerator {
    private $rootDir;
    private $outputDir;
    private $excludedDirs = ['vendor', 'node_modules', 'storage', 'bootstrap/cache'];
    private $config = [
        'include_tests' => true,
        'include_migrations' => true,
        'include_views' => true,
        'generate_diagrams' => true
    ];

    public function __construct($rootDir, $outputDir, $config = []) {
        $this->rootDir = $rootDir;
        $this->outputDir = $outputDir;
        $this->config = array_merge($this->config, $config);
    }

    public function generate() {
        $this->scanControllers();
        $this->scanModels();
        $this->scanRoutes();
        $this->scanTests();
        $this->scanMigrations();
        $this->scanViews();
        $this->generateDiagrams();
        $this->generateIndex();
        $this->generateChangelog();
    }

    private function scanControllers() {
        $controllerDir = $this->rootDir . '/app/Http/Controllers';
        if (!is_dir($controllerDir)) return;

        $content = "# Controllers Documentation\n\n";
        foreach (glob($controllerDir . '/*.php') as $file) {
            $className = basename($file, '.php');
            $content .= "## {$className}\n\n";
            $content .= $this->parsePhpFile($file);
            $content .= $this->generateApiDocs($file);
        }

        file_put_contents($this->outputDir . '/api/controllers.md', $content);
    }

    private function scanModels() {
        $modelDir = $this->rootDir . '/app/Models';
        if (!is_dir($modelDir)) return;

        $content = "# Models Documentation\n\n";
        foreach (glob($modelDir . '/*.php') as $file) {
            $className = basename($file, '.php');
            $content .= "## {$className}\n\n";
            $content .= $this->parsePhpFile($file);
            $content .= $this->generateModelRelations($file);
        }

        file_put_contents($this->outputDir . '/api/models.md', $content);
    }

    private function scanRoutes() {
        $routesFile = $this->rootDir . '/routes/web.php';
        if (!file_exists($routesFile)) return;

        $content = "# Routes Documentation\n\n";
        $content .= "## Web Routes\n\n";
        $content .= $this->parseRoutesFile($routesFile);

        file_put_contents($this->outputDir . '/api/routes.md', $content);
    }

    private function scanTests() {
        if (!$this->config['include_tests']) return;
        
        $testDir = $this->rootDir . '/tests';
        if (!is_dir($testDir)) return;

        $content = "# Test Documentation\n\n";
        foreach (glob($testDir . '/**/*.php') as $file) {
            $className = basename($file, '.php');
            $content .= "## {$className}\n\n";
            $content .= $this->parsePhpFile($file);
        }

        file_put_contents($this->outputDir . '/development/tests.md', $content);
    }

    private function scanMigrations() {
        if (!$this->config['include_migrations']) return;
        
        $migrationDir = $this->rootDir . '/database/migrations';
        if (!is_dir($migrationDir)) return;

        $content = "# Database Migrations\n\n";
        foreach (glob($migrationDir . '/*.php') as $file) {
            $className = basename($file, '.php');
            $content .= "## {$className}\n\n";
            $content .= $this->parseMigrationFile($file);
        }

        file_put_contents($this->outputDir . '/development/migrations.md', $content);
    }

    private function scanViews() {
        if (!$this->config['include_views']) return;
        
        $viewDir = $this->rootDir . '/resources/views';
        if (!is_dir($viewDir)) return;

        $content = "# View Templates\n\n";
        foreach (glob($viewDir . '/**/*.blade.php') as $file) {
            $viewName = str_replace($viewDir . '/', '', $file);
            $viewName = str_replace('.blade.php', '', $viewName);
            $content .= "## {$viewName}\n\n";
            $content .= $this->parseViewFile($file);
        }

        file_put_contents($this->outputDir . '/development/views.md', $content);
    }

    private function parsePhpFile($file) {
        $content = file_get_contents($file);
        $doc = '';
        
        // Extract class documentation
        if (preg_match('/\/\*\*\s*\n\s*\*\s*([^\n]+)/', $content, $matches)) {
            $doc .= $matches[1] . "\n\n";
        }

        // Extract method documentation
        preg_match_all('/\/\*\*\s*\n\s*\*\s*([^\n]+)\s*\n\s*\*\/\s*\n\s*(?:public|protected|private)\s+function\s+(\w+)/', $content, $matches, PREG_SET_ORDER);
        
        if (!empty($matches)) {
            $doc .= "### Methods\n\n";
            foreach ($matches as $match) {
                $doc .= "#### {$match[2]}\n";
                $doc .= $match[1] . "\n\n";
            }
        }

        return $doc;
    }

    private function parseRoutesFile($file) {
        $content = file_get_contents($file);
        $doc = '';
        
        preg_match_all('/Route::(get|post|put|delete|patch)\s*\(\s*[\'"]([^\'"]+)[\'"]\s*,\s*([^\)]+)\)/', $content, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $method = strtoupper($match[1]);
            $path = $match[2];
            $handler = trim($match[3]);
            
            $doc .= "### {$method} {$path}\n";
            $doc .= "Handler: {$handler}\n\n";
        }

        return $doc;
    }

    private function generateApiDocs($file) {
        $content = file_get_contents($file);
        $apiDocs = "\n### API Endpoints\n\n";
        
        preg_match_all('/@api\s+(\w+)\s+([^\n]+)/', $content, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $method = strtoupper($match[1]);
            $path = $match[2];
            $apiDocs .= "- {$method} {$path}\n";
        }
        
        return $apiDocs;
    }

    private function generateModelRelations($file) {
        $content = file_get_contents($file);
        $relations = "\n### Model Relations\n\n";
        
        preg_match_all('/public\s+function\s+(\w+)\s*\(\s*\)\s*{\s*return\s+\$this->(hasMany|belongsTo|hasOne|belongsToMany)/', $content, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $relationName = $match[1];
            $relationType = $match[2];
            $relations .= "- {$relationName} ({$relationType})\n";
        }
        
        return $relations;
    }

    private function parseMigrationFile($file) {
        $content = file_get_contents($file);
        $doc = '';
        
        if (preg_match('/Schema::create\(\s*[\'"]([^\'"]+)[\'"]/', $content, $matches)) {
            $tableName = $matches[1];
            $doc .= "Table: {$tableName}\n\n";
            
            preg_match_all('/\$table->(\w+)\s*\(\s*[\'"]([^\'"]+)[\'"]/', $content, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $columnType = $match[1];
                $columnName = $match[2];
                $doc .= "- {$columnName} ({$columnType})\n";
            }
        }
        
        return $doc;
    }

    private function parseViewFile($file) {
        $content = file_get_contents($file);
        $doc = '';
        
        // Extract section comments
        preg_match_all('/{{--\s*([^}]+)\s*--}}/', $content, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $doc .= "- {$match[1]}\n";
        }
        
        return $doc;
    }

    private function generateDiagrams() {
        if (!$this->config['generate_diagrams']) return;
        
        // Generate class diagram
        $this->generateClassDiagram();
        
        // Generate sequence diagram
        $this->generateSequenceDiagram();
    }

    private function generateClassDiagram() {
        $content = "# Class Diagram\n\n";
        $content .= "```mermaid\n";
        $content .= "classDiagram\n";
        
        // Add classes and relationships
        $modelDir = $this->rootDir . '/app/Models';
        if (is_dir($modelDir)) {
            foreach (glob($modelDir . '/*.php') as $file) {
                $className = basename($file, '.php');
                $content .= "    class {$className}\n";
            }
        }
        
        $content .= "```\n";
        
        file_put_contents($this->outputDir . '/architecture/class-diagram.md', $content);
    }

    private function generateSequenceDiagram() {
        $content = "# Sequence Diagram\n\n";
        $content .= "```mermaid\n";
        $content .= "sequenceDiagram\n";
        
        // Add sequence diagram content
        $content .= "    participant User\n";
        $content .= "    participant Controller\n";
        $content .= "    participant Model\n";
        $content .= "    participant Database\n";
        
        $content .= "```\n";
        
        file_put_contents($this->outputDir . '/architecture/sequence-diagram.md', $content);
    }

    private function generateChangelog() {
        $content = "# Changelog\n\n";
        
        // Get git log
        $log = shell_exec('git log --pretty=format:"%h - %an, %ar : %s"');
        if ($log) {
            $content .= $log;
        } else {
            $content .= "No changelog available.\n";
        }
        
        file_put_contents($this->outputDir . '/changelog.md', $content);
    }

    private function generateIndex() {
        $content = "# Referral System Documentation\n\n";
        $content .= "## Table of Contents\n\n";
        
        // API Documentation
        $content .= "- [API Documentation](api/)\n";
        $content .= "  - [Controllers](api/controllers.md)\n";
        $content .= "  - [Models](api/models.md)\n";
        $content .= "  - [Routes](api/routes.md)\n";
        
        // Development Documentation
        $content .= "- [Development Guide](development/)\n";
        $content .= "  - [Setup Guide](development/setup.md)\n";
        if ($this->config['include_tests']) {
            $content .= "  - [Tests](development/tests.md)\n";
        }
        if ($this->config['include_migrations']) {
            $content .= "  - [Migrations](development/migrations.md)\n";
        }
        if ($this->config['include_views']) {
            $content .= "  - [Views](development/views.md)\n";
        }
        
        // Architecture Documentation
        $content .= "- [Architecture](architecture/)\n";
        $content .= "  - [System Overview](architecture/system-overview.md)\n";
        if ($this->config['generate_diagrams']) {
            $content .= "  - [Class Diagram](architecture/class-diagram.md)\n";
            $content .= "  - [Sequence Diagram](architecture/sequence-diagram.md)\n";
        }
        
        // User Guides
        $content .= "- [User Guides](guides/)\n";
        $content .= "  - [User Manual](guides/user-manual.md)\n";
        
        // Changelog
        $content .= "- [Changelog](changelog.md)\n";

        file_put_contents($this->outputDir . '/index.md', $content);
    }
}

// Usage
$generator = new DocumentationGenerator(
    __DIR__ . '/..',
    __DIR__,
    [
        'include_tests' => true,
        'include_migrations' => true,
        'include_views' => true,
        'generate_diagrams' => true
    ]
);
$generator->generate(); 