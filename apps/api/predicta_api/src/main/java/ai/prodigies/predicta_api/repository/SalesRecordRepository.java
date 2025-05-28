package ai.prodigies.predicta_api.repository;

import ai.prodigies.predicta_api.model.SalesRecord;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface SalesRecordRepository extends JpaRepository<SalesRecord, Long> {}