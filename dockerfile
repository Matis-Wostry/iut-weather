FROM donovanbroquin/iut-laravel:laravel

RUN addgroup --gid ${GROUP_ID} laravel && \
    adduser --disabled-password --gecos '' --uid ${USER_ID} --gid ${GROUP_ID} laravel

USER laravel:laravel