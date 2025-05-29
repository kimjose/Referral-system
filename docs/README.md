# Angaza Referral System Documentation

Welcome to the Angaza Referral System documentation. This documentation provides comprehensive information about the system's features, setup, and usage.

## Documentation Sections

### Getting Started
- [Getting Started Guide](getting_started.md) - Complete guide for new users
  - System Requirements
  - First-Time Setup
  - System Navigation
  - Basic Operations
  - Security Setup
  - Training Resources

### Features
- [Features Documentation](features.md) - Detailed feature overview
  - Core Features
  - Advanced Features
  - Administrative Features
  - Integration Features
  - Customization Features

### Troubleshooting
- [Troubleshooting Guide](troubleshooting.md) - Common issues and solutions
  - Authentication Issues
  - System Access
  - Data Issues
  - Performance Issues
  - Integration Issues
  - Mobile Issues

### API Documentation
- [API Documentation](api.md) - API endpoints and usage
  - Authentication
  - Endpoints
  - Request/Response Formats
  - Error Handling

### Database Schema
- [Database Schema](database_schema.md) - Database structure and relationships
  - Tables
  - Relationships
  - Indexes
  - Data Types

### System Sequence
- [System Sequence](system_sequence.md) - System workflows and processes
  - Core Sequences
  - Integration Flows
  - Error Handling
  - Security Measures

### Migrations
- [Migration Guide](migrations.md) - Database migration documentation
  - Migration Files
  - Running Migrations
  - Seeding Data
  - Best Practices

## Quick Links

- [User Guide](user_guide.md) - Complete user manual
- [API Reference](api.md) - API documentation
- [Troubleshooting](troubleshooting.md) - Common issues and solutions
- [Getting Started](getting_started.md) - New user guide
- [Features](features.md) - System features overview

## Support

For additional support:
- Email: support@angaza-referral.com
- Phone: +254 XXX XXX XXX
- Live Chat: Available 24/7
- Knowledge Base: [Documentation Home](README.md)

# Angaza Referral System Documentation Setup Guide

This guide provides step-by-step instructions for setting up and maintaining the documentation for the Angaza Referral System.

## Prerequisites

1. Python 3.x installed
2. pip (Python package manager)
3. Git (for version control)

## Installation Steps

1. **Create and activate a virtual environment**:
   ```bash
   # Create virtual environment
   python -m venv .venv
   
   # Activate virtual environment
   # On macOS/Linux:
   source .venv/bin/activate
   # On Windows:
   .venv\Scripts\activate
   ```

2. **Install required packages**:
   ```bash
   pip install mkdocs-material
   pip install mkdocs-git-revision-date-localized-plugin
   pip install mkdocs-minify-plugin
   ```

3. **Verify installation**:
   ```bash
   mkdocs --version
   ```

## Project Structure

```
docs/
├── README.md                 # This guide
├── mkdocs.yml               # MkDocs configuration
└── new_docs/                # Documentation source files
    ├── index.md             # Home page
    ├── guides/              # User guides
    ├── architecture/        # System architecture docs
    ├── development/         # Development guides
    ├── api/                 # API documentation
    └── standards/           # Standards documentation
```

## Configuration

1. **Basic Configuration** (`mkdocs.yml`):
   ```yaml
   site_name: Angaza Referral System Documentation
   site_description: Comprehensive documentation for the Angaza Referral System
   site_author: Angaza Development Team
   
   theme:
     name: material
     features:
       - navigation.tabs
       - navigation.sections
       - navigation.expand
       - search.highlight
       - content.code.copy
   ```

2. **Markdown Extensions**:
   ```yaml
   markdown_extensions:
     - pymdownx.highlight
     - pymdownx.superfences:
         custom_fences:
           - name: mermaid
             class: mermaid
             format: !!python/name:pymdownx.superfences.fence_code_format
     # ... other extensions
   ```

3. **Plugins**:
   ```yaml
   plugins:
     - search
     - git-revision-date-localized
     - minify:
         minify_html: true
   ```

## Running the Documentation

1. **Start the development server**:
   ```bash
   cd docs
   mkdocs serve
   ```

2. **Access the documentation**:
   - Open your browser and navigate to `http://127.0.0.1:8000/`
   - The documentation will automatically reload when you make changes

3. **Building for production**:
   ```bash
   mkdocs build
   ```
   This creates a `site` directory with the static documentation.

## Common Issues and Solutions

1. **Port already in use**:
   ```bash
   # Find the process using port 8000
   lsof -i :8000 | grep LISTEN
   
   # Kill the process
   kill <PID>
   ```

2. **Missing plugins**:
   - Ensure all required plugins are installed
   - Check `mkdocs.yml` for correct plugin configuration

3. **Mermaid diagrams not rendering**:
   - Verify the Mermaid configuration in `mkdocs.yml`
   - Check for proper code fence syntax in markdown files

## Best Practices

1. **Documentation Structure**:
   - Keep related content together
   - Use clear, descriptive file names
   - Maintain consistent formatting

2. **Writing Documentation**:
   - Use clear, concise language
   - Include examples where appropriate
   - Keep diagrams up to date
   - Use proper markdown formatting

3. **Version Control**:
   - Commit documentation changes regularly
   - Use meaningful commit messages
   - Keep documentation in sync with code changes

## Adding New Content

1. **Create new markdown files** in the appropriate directory
2. **Update navigation** in `mkdocs.yml`
3. **Add content** using markdown syntax
4. **Test locally** using `mkdocs serve`
5. **Commit changes** to version control

## Maintenance

1. **Regular Updates**:
   - Review and update content regularly
   - Keep dependencies up to date
   - Monitor for broken links

2. **Backup**:
   - Keep regular backups of documentation
   - Maintain version history

3. **Performance**:
   - Optimize images and diagrams
   - Monitor build times
   - Check for broken links

## Additional Resources

- [MkDocs Documentation](https://www.mkdocs.org/)
- [Material for MkDocs](https://squidfunk.github.io/mkdocs-material/)
- [Mermaid.js Documentation](https://mermaid-js.github.io/mermaid/#/)

## Support

For documentation-related issues:
1. Check the common issues section
2. Review the MkDocs documentation
3. Contact the development team

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

Remember to:
- Follow the established documentation style
- Test your changes locally
- Update the navigation if needed
- Include clear commit messages

## Deployment Guide

### Prerequisites for Production Deployment

1. **Server Requirements**:
   - Web server (Apache/Nginx)
   - SSL certificate for HTTPS
   - Domain name (optional)
   - SSH access to server

2. **Build Requirements**:
   - Python 3.x
   - pip
   - Git

### Deployment Steps

1. **Build the Documentation**:
   ```bash
   # Navigate to docs directory
   cd docs
   
   # Build the documentation
   mkdocs build
   ```
   This creates a `site` directory with static files.

2. **Configure Web Server**:

   **Apache Configuration**:
   ```apache
   <VirtualHost *:80>
       ServerName docs.yourdomain.com
       DocumentRoot /path/to/docs/site
       
       <Directory /path/to/docs/site>
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
       
       # Redirect to HTTPS
       RewriteEngine On
       RewriteCond %{HTTPS} off
       RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   </VirtualHost>
   ```

   **Nginx Configuration**:
   ```nginx
   server {
       listen 80;
       server_name docs.yourdomain.com;
       return 301 https://$server_name$request_uri;
   }

   server {
       listen 443 ssl;
       server_name docs.yourdomain.com;
       
       ssl_certificate /path/to/cert.pem;
       ssl_certificate_key /path/to/key.pem;
       
       root /path/to/docs/site;
       index index.html;
       
       location / {
           try_files $uri $uri/ /index.html;
       }
   }
   ```

3. **Deploy to Server**:
   ```bash
   # Using rsync (recommended)
   rsync -avz --delete site/ user@your-server:/path/to/web/root/
   
   # Or using scp
   scp -r site/* user@your-server:/path/to/web/root/
   ```

4. **Set Permissions**:
   ```bash
   # Set correct ownership
   chown -R www-data:www-data /path/to/web/root
   
   # Set correct permissions
   chmod -R 755 /path/to/web/root
   ```

### Automated Deployment

1. **Create Deployment Script** (`deploy.sh`):
   ```bash
   #!/bin/bash
   
   # Build documentation
   cd docs
   mkdocs build
   
   # Deploy to server
   rsync -avz --delete site/ user@your-server:/path/to/web/root/
   
   # Set permissions
   ssh user@your-server "chown -R www-data:www-data /path/to/web/root && chmod -R 755 /path/to/web/root"
   ```

2. **Make Script Executable**:
   ```bash
   chmod +x deploy.sh
   ```

3. **Run Deployment**:
   ```bash
   ./deploy.sh
   ```

### Continuous Deployment

1. **GitHub Actions Workflow** (`.github/workflows/deploy-docs.yml`):
   ```yaml
   name: Deploy Documentation
   
   on:
     push:
       branches:
         - main
       paths:
         - 'docs/**'
   
   jobs:
     deploy:
       runs-on: ubuntu-latest
       steps:
         - uses: actions/checkout@v2
         
         - name: Set up Python
           uses: actions/setup-python@v2
           with:
             python-version: '3.x'
             
         - name: Install dependencies
           run: |
             python -m pip install --upgrade pip
             pip install mkdocs-material
             pip install mkdocs-git-revision-date-localized-plugin
             pip install mkdocs-minify-plugin
             
         - name: Build documentation
           run: |
             cd docs
             mkdocs build
             
         - name: Deploy to server
           uses: burnett01/rsync-deployments@5.2.1
           with:
             switches: -avz --delete
             path: docs/site/
             remote_path: ${{ secrets.REMOTE_PATH }}
             remote_host: ${{ secrets.REMOTE_HOST }}
             remote_user: ${{ secrets.REMOTE_USER }}
             remote_key: ${{ secrets.REMOTE_KEY }}
   ```

### Post-Deployment Checklist

1. **Verify Deployment**:
   - Check all pages load correctly
   - Verify all links work
   - Test search functionality
   - Confirm Mermaid diagrams render properly
   - Test responsive design on different devices

2. **Monitor Performance**:
   - Set up monitoring for server resources
   - Configure error logging
   - Set up uptime monitoring
   - Monitor SSL certificate expiration

3. **Backup Strategy**:
   - Regular backups of documentation source
   - Backup of server configuration
   - Document recovery procedures

### Troubleshooting Deployment

1. **Common Issues**:
   - 404 errors: Check file permissions and paths
   - SSL issues: Verify certificate configuration
   - Performance issues: Check server resources
   - Build failures: Review build logs

2. **Debugging Steps**:
   ```bash
   # Check web server logs
   tail -f /var/log/apache2/error.log  # Apache
   tail -f /var/log/nginx/error.log    # Nginx
   
   # Check file permissions
   ls -la /path/to/web/root
   
   # Test web server configuration
   apache2ctl -t  # Apache
   nginx -t       # Nginx
   ```

### Security Considerations

1. **SSL/TLS Configuration**:
   - Use strong SSL/TLS protocols
   - Enable HSTS
   - Configure secure headers

2. **Access Control**:
   - Implement IP restrictions if needed
   - Set up authentication for sensitive areas
   - Configure proper file permissions

3. **Regular Updates**:
   - Keep dependencies updated
   - Monitor security advisories
   - Regular security audits 