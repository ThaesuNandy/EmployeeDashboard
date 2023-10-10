<?php $pager->setSurroundCount(3)  ?>

<div class="row">

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
     <ul class="pagination">
            <?php $pager->hasPrevious() ?>
            <?php if($pager->hasPrevious()): ?>
                <li class="page-item">
                    <a href="<?= $pager->getFirst() ?>" class="page-link">First</a>
                </li>
                <li class="page-item">
                    <a href="<?= $pager->getPrevious() ?>" class="page-link">Previous</a>
                </li>
            <?php endif; ?>
          
          
            <?php foreach($pager->links() as $link): ?>
                <li class="page-item <?= $link['active'] ? 'active' : '' ?>"><a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a></li>
            <?php endforeach; ?>
       

            <?php if($pager->hasNext()): ?>
                <li class="page-item">
                    <a href="<?= $pager->getNext() ?>" class="page-link">Next</a>
                </li>
                <li class="page-item">
                    <a href="<?= $pager->getLast() ?>" class="page-link">Last</a>
                </li>
            <?php endif; ?>
        
        </ul>
    </div>
  
 
  
</div>