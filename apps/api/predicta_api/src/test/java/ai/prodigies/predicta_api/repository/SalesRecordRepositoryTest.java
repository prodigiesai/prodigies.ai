package ai.prodigies.predicta_api.repository;

import ai.prodigies.predicta_api.model.SalesRecord;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.orm.jpa.DataJpaTest;
import org.springframework.test.context.ActiveProfiles;
import org.springframework.boot.autoconfigure.mongo.MongoAutoConfiguration;
import org.springframework.boot.autoconfigure.data.mongo.MongoRepositoriesAutoConfiguration;


import static org.junit.jupiter.api.Assertions.assertNotNull;


@DataJpaTest(excludeAutoConfiguration = {
        org.springframework.boot.autoconfigure.mongo.MongoAutoConfiguration.class,
        org.springframework.boot.autoconfigure.data.mongo.MongoDataAutoConfiguration.class
})
@ActiveProfiles("test")
public class SalesRecordRepositoryTest {

    @Autowired
    private SalesRecordRepository salesRecordRepository;

    @Test
    void contextLoads() {
        assertNotNull(salesRecordRepository);
    }

    @Test
    public void testSaveSalesRecord() {
        SalesRecord salesRecord = new SalesRecord();
        salesRecord.setCustomerName("John Doe");
        salesRecord.setProductName("Smartphone");
        salesRecord.setQuantity(10);
        salesRecord.setPrice(3000.00);

        salesRecordRepository.save(salesRecord);

        System.out.println("Saved Sales Record: " + salesRecord);
    }
}