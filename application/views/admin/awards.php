<header class="page-header">
        <h2>Awards</h2>
</header>

<!-- start: page -->
<div class="row">

<div class="col-md-12">
    <section class="panel panel-featured panel-featured-primary">
            <header class="panel-heading">
                    

                    <h2 class="panel-title">View All Awards</h2>
            </header>
            <div class="panel-body">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Image</th>
                    </tr>
                    <?php foreach($awards as $award): ?>
                    <tr>
                        <td><?php echo $award->id?></td>
                        <td><?php echo $award->name?></td>
                        <td><?php echo $award->descrip?></td>
                        <td><?php echo $types[$award->type]?></td>
                        <td><?php echo $award->award_image?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>               
                
            </div>
    </section>
</div>
</div>