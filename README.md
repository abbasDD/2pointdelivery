<h1>2 Point Web App</h1>

<p>A project designed to provide delivery services for the Point Web App.</p>

<p>Follow the steps below to install this project:</p>

<ol>
  <li>First, clone this project and place it in your <code>htdocs</code> folder.</li>
  <li>Duplicate the <code>.env.example</code> file and rename it to <code>.env</code>.</li>
  <li>Update the following in the <code>.env</code> file:
    <ul>
      <li><code>APP_NAME</code></li>
      <li><code>APP_URL</code></li>
      <li><code>ASSET_URL</code></li>
    </ul>
  </li>
  <li>Create a database and update the database credentials in the <code>.env</code> file.</li>
  <li>Run the following command to install all Composer packages: <code>composer install</code></li>
  <li>Run the following command to set up the database: <code>php artisan migrate</code></li>
  <li>Run the following command to seed data into the database: <code>php artisan db:seed</code></li>
  <li>Navigate to <code>database\predefined-data</code> and import the SQL files for countries, states, and cities in this order:
    <ol>
      <li>Start with <strong>countries</strong></li>
      <li>Then <strong>states</strong></li>
      <li>Finally, <strong>cities</strong></li>
      <li>Later, <strong>industries</strong></li>
    </ol>
    <p>Remember to follow the order: countries first, then states, and finally cities.</p>
  </li>
  <li>Run <code>php artisan passport:client --personal</code> to create a Passport token.</li>
</ol>
