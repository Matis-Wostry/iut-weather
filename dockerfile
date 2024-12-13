FROM donovanbroquin/iut-laravel:laravel

# Add the laravel group and user with the provided GID and UID
RUN addgroup --gid ${GROUP_ID} laravel && \
    adduser --disabled-password --gecos '' --uid ${USER_ID} --gid ${GROUP_ID} laravel

# Change ownership of the entire application directory
# Ensure the laravel user has permission to modify all files 
RUN chown -R laravel:laravel /var/www/app && \
    chmod -R 775 /var/www/app

# Switch to the laravel user
USER laravel:laravel