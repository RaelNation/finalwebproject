<?php include 'navbar_admin.php'; 

if (isset($_GET['id'])) {
    $film_id = $_GET['id'];

    $films = query("SELECT * FROM `films` WHERE `film_id` = '$film_id' LIMIT 1;");
    
    if (!empty($films)) {
        $film_name = $films[0]['name'];

        // $comments = query("SELECT comments.*, users.photo, users.fullname 
        //                   FROM comments 
        //                   JOIN users ON comments.user_id = users.id 
        //                   WHERE `film_id` = '$film_id' ORDER BY comment_date DESC");

        $count_comment = query("SELECT COUNT(*) as total FROM comments WHERE `film_id` = '$film_id';");
        $total_comment = $count_comment[0]['total'];
        
        $recommended = query("SELECT films.*
                            FROM films
                            JOIN film_genres ON films.film_id = film_genres.film_id
                            JOIN genres ON film_genres.genre_id = genres.id
                            WHERE genres.id IN (
                                SELECT genre_id FROM film_genres WHERE film_id = '$film_id'
                            ) AND films.film_id != '$film_id' LIMIT 10;");

        $film_genre = query("SELECT genres.name
                            FROM films
                            JOIN film_genres ON films.film_id = film_genres.film_id
                            JOIN genres ON film_genres.genre_id = genres.id
                            WHERE films.film_id = '$film_id';");

        $similar_films = query("SELECT f.film_id, f.name, f.poster, f.rating, f.release_year AS year, f.duration
                                  FROM films f JOIN film_tags ft1 ON f.film_id = ft1.film_id JOIN film_tags ft2 ON ft1.film_tag = ft2.film_tag
                                  WHERE ft2.film_id = $film_id AND f.film_id != $film_id
                                  GROUP BY f.film_id, f.name, f.poster, f.rating, f.release_year, f.duration LIMIT 7;");

        if (empty($similar_films)) {
            $similar_films = query("SELECT film_id, name, poster, rating, release_year AS year, duration 
                                    FROM `films` 
                                    ORDER BY release_year DESC 
                                    LIMIT 5;");
        }
    } else {
        echo "Film dengan ID $film_id tidak ditemukan.";
    }
} else {
    header('Location: 404.html'); 
    exit;
}

?>
<div class="film-trailer">
    <div class="video-wrapper">
        <iframe src="<?php echo $films[0]['link']; ?>&autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>
</div>

<section>
  <div class="carousel-container">
    <div class="mySlides">
      <div class="film-banner">
        <img src="./image/movie-banner/<?php echo $films[0]['banner']; ?>" style="width: 100%" />
      </div>
    </div>
  </div>
</section>
<section class="section-margin">
  <div class="film-detail-container">
    <div class="detail-content1">
      <div class="film-detail">
        <div class="film-poster">
          <img src="./image/movie-poster/<?php echo $films[0]['poster']; ?>" alt="">
        </div>
        <div class="film-detail-information">
          <h3 class="film-detail-title">
            <?php echo $films[0]['name']; ?>
          </h3>
          <div class="film-prop">
            <div class="film-rate"><?php echo $films[0]['rate']; ?></div>
            <div class="film-rating"><i class="fa-solid fa-star"></i><?php echo $films[0]['rating']; ?></div>
            <div class="film-duration"><?php echo $films[0]['duration']; ?></div>
          </div>
          <p class="film-detail-synopsis">
            <?php echo $films[0]['synopsis']; ?>
          </p>
           <div class="film-info">
                <div class="film-type info-prop"><span class="label">Type:</span> <span class="content"><?php echo $films[0]['type']; ?></span></div>
                <div class="film-country info-prop"><span class="label">Country:</span> <span class="content"><?php echo $films[0]['country']; ?></span></div>
                <div class="film-genre info-prop">
                    <span class="label">Genre:</span> 
                    <span class="content">
                        <?php
                        $genres = array();
                        foreach ($film_genre as $genre) {
                            $genres[] = $genre['name'];
                        }
                        echo implode(', ', $genres);
                        ?>
                    </span>
                </div>
                <div class="film-release info-prop"><span class="label">Release:</span> <span class="content"><?php echo $films[0]['release_date']; ?></span></div>
                <div class="film-director info-prop"><span class="label">Director:</span> <span class="content"><?php echo $films[0]['director']; ?></span></div>
                <div class="film-production info-prop"><span class="label">Production:</span> <span class="content"><?php echo $films[0]['production']; ?></span></div>
                <div class="film-cast info-prop"><span class="label">Cast:</span> <span class="content"><?php echo $films[0]['cast']; ?></span></div>
                <div class="film-status info-prop">
                  <span class="label">Status:</span>
                  <span class="content">
                    <a href="" id="<?php echo $films[0]['status']; ?>">Default Status</a>
                  </span>
                </div>
                <!-- <h5>Give Rating to this Film!</h5> -->
                <div class="rating-card">
                          <div class="left">
                            <h2>
                              <img src="./image/smile-2.svg" />
                            </h2>
                            <ul class="ratings">
                              <i class="fa-solid fa-star" id="star-1"></i>

                              <i class="fa-solid fa-star" id="star-2"></i>

                              <i class="fa-solid fa-star" id="star-3"></i>

                              <i class="fa-solid fa-star" id="star-4"></i>

                              <i class="fa-solid fa-star" id="star-5"></i>
                            </ul>
                            <button onclick="onSubmit()">Rate</button>
                          </div>
                </div>
              </div>
        </div>
        
      </div>
      <div class="film-related">
        <h2>Related</h2>
          <?php foreach ($similar_films as $related): ?>
            <div class="film-related-item">
              <a href="film-detail_admin.php?id=<?= htmlspecialchars($related['film_id']) ?>">
                <div class="film-poster">
                  <img src="./image/movie-poster/<?= htmlspecialchars($related['poster']) ?>" alt="">
                </div>
                <div class="film-related-information">
                  <div class="film-related-title">
                    <h5><?= htmlspecialchars($related['name']) ?></h5>
                  </div>
                  <div class="film-prop">
                    <div class="film-rating"><i class="fa-solid fa-star"></i><?= htmlspecialchars($related['rating']) ?></div>
                    <div class="film-year"><?= htmlspecialchars($related['year']) ?></div>
                    <div class="film-duration"><?= htmlspecialchars($related['duration']) ?></div>
                  </div>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
      </div>
    </div>
       

    <div class="detail-content2">
<div class="film-comment">
    <h2>COMMENT</h2>
    <div class="comment-content">
        <h5 class="total-comment"><?php echo $total_comment; ?> Comment</h5>
        <div class="comment-field">
            <div class="comment-user-input">
                <div class="user-photo">
                    <img src="./image/user-picture/default.jpg" width="60px" style="border-radius: 50%;" alt="">
                </div>
                <div class="comment-input">
                  <form id="commentForm" class="form_data">
                      <textarea id="myTextarea" name="comment" class="auto-resize comment-form form_data" placeholder="Add a comment..."></textarea>
                      <input type="hidden" name="film_id" value="<?= $film_id ?>" class="form_data">
                      <input type="hidden" name="fullname" value="<?= $user_info[0]['fullname'] ?>" class="form_data">
                      <div class="toolbar">
                          <div class="text-tool">
                              <button type="button" onclick="wrapText('b')"><b>B</b></button>
                              <button type="button" onclick="wrapText('i')"><i>I</i></button>
                              <button type="button" onclick="wrapText('u')"><u>U</u></button>
                              <button type="button" onclick="wrapText('s')"><s>S</s></button>
                              <button type="button" onclick="wrapText('a', 'href=&quot;#&quot;')"><i class="fa-solid fa-link"></i></button>
                              <button type="button" onclick="wrapText('div')"><i class="fa-solid fa-eye-slash"></i></button>
                          </div>
                          <button type="submit" id="submit-comment" name="submit" class="send-button">Comment</button>
                      </div>
                  </form>
                  <?php if(isset($error_message)) : ?>
                      <p><?= $error_message ?></p>
                  <?php endif; ?>
              </div>
            </div>
            <?php include 'fetch_comments.php'; ?>
        </div>
    </div>
</div>


      <div class="film-recommend">
        <h2>Recommended</h2>
        <?php foreach($recommended as $recommend) : ?>
          <div class="film-related-item">
            <a href="film-detail_admin.php?id=<?= $recommend['film_id'] ?>">
              <div class="film-poster">
                <img src="./image/movie-poster/<?= $recommend['poster'] ?>" alt="">
              </div>
              <div class="film-related-information">
                <div class="film-related-title">
                  <h5><?= $recommend['name'] ?></h5>
                </div>
                <div class="film-prop">
                  <div class="film-rating"><i class="fa-solid fa-star"></i><?= $recommend['rating'] ?></div>
                  <div class="film-year"><?= $recommend['release_year'] ?></div>
                  <div class="film-duration"><?= $recommend['duration'] ?></div>
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php include 'footer.php'; ?>

